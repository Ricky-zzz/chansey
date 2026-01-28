<?php

namespace App\Services;

use App\Models\PatientFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PatientFileService
{
    /**
     * Document type mappings for form inputs.
     */
    public const DOCUMENT_TYPES = [
        'doc_valid_id' => 'Valid ID',
        'doc_loa' => 'Insurance LOA',
        'doc_consent' => 'General Consent',
        'doc_privacy' => 'Privacy Notice',
        'doc_mdr' => 'PhilHealth MDR',
    ];

    /**
     * Upload a patient file and create the database record.
     *
     * @param UploadedFile $file The uploaded file
     * @param int $patientId The patient ID
     * @param int $admissionId The admission ID
     * @param string $documentType The type of document
     * @param int|null $uploadedById The user ID who uploaded (defaults to Auth::id())
     * @param string $disk The storage disk to use
     * @return PatientFile The created PatientFile record
     * @throws \Exception If file upload fails
     */
    public function upload(
        UploadedFile $file,
        int $patientId,
        int $admissionId,
        string $documentType,
        ?int $uploadedById = null,
        string $disk = 'private'
    ): PatientFile {
        $uploadedById = $uploadedById ?? Auth::id();
        $storagePath = $this->getStoragePath($patientId, $admissionId);
        $fileName = $this->generateSafeFileName($file);

        $path = $file->storeAs($storagePath, $fileName, $disk);

        if ($path === false) {
            throw new \Exception("Failed to store file: {$file->getClientOriginalName()}");
        }

        return PatientFile::create([
            'patient_id' => $patientId,
            'admission_id' => $admissionId,
            'uploaded_by_id' => $uploadedById,
            'document_type' => $documentType,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);
    }

    /**
     * Upload multiple files from a request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $patientId
     * @param int $admissionId
     * @param array|null $fileMap Custom file input to document type mapping (defaults to DOCUMENT_TYPES)
     * @param bool $throwOnError Whether to throw exceptions or just log warnings
     * @return array Array of created PatientFile records
     */
    public function uploadFromRequest(
        $request,
        int $patientId,
        int $admissionId,
        ?array $fileMap = null,
        bool $throwOnError = false,
        string $disk = 'private'
    ): array {
        $fileMap = $fileMap ?? self::DOCUMENT_TYPES;
        $uploadedFiles = [];

        foreach ($fileMap as $inputName => $docType) {
            if ($request->hasFile($inputName)) {
                try {
                    $uploadedFiles[] = $this->upload(
                        $request->file($inputName),
                        $patientId,
                        $admissionId,
                        $docType,
                        null,
                        $disk
                    );
                } catch (\Exception $e) {
                    if ($throwOnError) {
                        throw $e;
                    }
                    Log::warning("Failed to upload {$inputName} for admission {$admissionId}: " . $e->getMessage());
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Replace an existing file with a new one.
     *
     * @param UploadedFile $file The new file
     * @param int $patientId
     * @param int $admissionId
     * @param string $documentType
     * @param string $disk
     * @return PatientFile
     */
    public function replaceOrCreate(
        UploadedFile $file,
        int $patientId,
        int $admissionId,
        string $documentType,
        string $disk = 'private'
    ): PatientFile {
        $oldFile = PatientFile::where('admission_id', $admissionId)
            ->where('document_type', $documentType)
            ->first();

        // Delete old file if exists
        if ($oldFile) {
            $this->deleteFile($oldFile, $disk);
        }

        $storagePath = $this->getStoragePath($patientId, $admissionId);
        $fileName = $this->generateSafeFileName($file);
        $path = $file->storeAs($storagePath, $fileName, $disk);

        if ($oldFile) {
            $oldFile->update([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_by_id' => Auth::id(),
            ]);
            return $oldFile->fresh();
        }

        return PatientFile::create([
            'patient_id' => $patientId,
            'admission_id' => $admissionId,
            'uploaded_by_id' => Auth::id(),
            'document_type' => $documentType,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);
    }

    /**
     * Update files from a request, replacing existing ones.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $patientId
     * @param int $admissionId
     * @param array|null $fileMap
     * @param string $disk
     * @return array
     */
    public function updateFromRequest(
        $request,
        int $patientId,
        int $admissionId,
        ?array $fileMap = null,
        string $disk = 'private'
    ): array {
        $fileMap = $fileMap ?? self::DOCUMENT_TYPES;
        $updatedFiles = [];

        foreach ($fileMap as $inputName => $docType) {
            if ($request->hasFile($inputName)) {
                try {
                    $updatedFiles[] = $this->replaceOrCreate(
                        $request->file($inputName),
                        $patientId,
                        $admissionId,
                        $docType,
                        $disk
                    );
                } catch (\Exception $e) {
                    Log::warning("Failed to update {$inputName} for admission {$admissionId}: " . $e->getMessage());
                }
            }
        }

        return $updatedFiles;
    }

    /**
     * Delete a patient file from storage and database.
     *
     * @param PatientFile $patientFile
     * @param string $disk
     * @return bool
     */
    public function deleteFile(PatientFile $patientFile, string $disk = 'private'): bool
    {
        if (Storage::disk($disk)->exists($patientFile->file_path)) {
            Storage::disk($disk)->delete($patientFile->file_path);
        }

        return $patientFile->delete();
    }

    /**
     * Get the storage path for patient files.
     *
     * @param int $patientId
     * @param int $admissionId
     * @return string
     */
    public function getStoragePath(int $patientId, int $admissionId): string
    {
        return "patient_records/{$patientId}/{$admissionId}";
    }

    /**
     * Generate a safe file name to prevent conflicts and security issues.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateSafeFileName(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Sanitize the filename
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $baseName);
        
        // Add timestamp to prevent collisions
        return $safeName . '_' . time() . '.' . $extension;
    }

    /**
     * Get all files for an admission.
     *
     * @param int $admissionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilesForAdmission(int $admissionId)
    {
        return PatientFile::where('admission_id', $admissionId)
            ->with('uploader')
            ->get();
    }

    /**
     * Copy files from one admission to another (useful for transfers).
     *
     * @param int $sourceAdmissionId
     * @param int $targetAdmissionId
     * @param int $targetPatientId
     * @param string $disk
     * @return array Array of new PatientFile records
     */
    public function copyFilesToAdmission(
        int $sourceAdmissionId,
        int $targetAdmissionId,
        int $targetPatientId,
        string $disk = 'private'
    ): array {
        $sourceFiles = PatientFile::where('admission_id', $sourceAdmissionId)->get();
        $copiedFiles = [];

        foreach ($sourceFiles as $sourceFile) {
            if (Storage::disk($disk)->exists($sourceFile->file_path)) {
                $newPath = $this->getStoragePath($targetPatientId, $targetAdmissionId) 
                    . '/' . basename($sourceFile->file_path);

                Storage::disk($disk)->copy($sourceFile->file_path, $newPath);

                $copiedFiles[] = PatientFile::create([
                    'patient_id' => $targetPatientId,
                    'admission_id' => $targetAdmissionId,
                    'uploaded_by_id' => Auth::id(),
                    'document_type' => $sourceFile->document_type,
                    'file_name' => $sourceFile->file_name,
                    'file_path' => $newPath,
                ]);
            }
        }

        return $copiedFiles;
    }
}
