<?php

namespace App\Http\Controllers;

use App\Models\PatientFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function view($id)
    {
        $fileRecord = PatientFile::findOrFail($id);

        // Use Storage facade for private disk (proper Laravel way)
        $disk = Storage::disk('private');

        if ($disk->exists($fileRecord->file_path)) {
            $fullPath = $disk->path($fileRecord->file_path);
            return response()->file($fullPath, [
                'Content-Type' => mime_content_type($fullPath),
            ]);
        }

        // Fallback: check legacy paths
        $privatePath = storage_path('app/private/' . $fileRecord->file_path);
        $publicPath = storage_path('app/public/' . $fileRecord->file_path);
        $legacyPath = storage_path('app/' . $fileRecord->file_path);

        if (file_exists($privatePath)) {
            return response()->file($privatePath);
        } elseif (file_exists($publicPath)) {
            return response()->file($publicPath);
        } elseif (file_exists($legacyPath)) {
            return response()->file($legacyPath);
        }

        abort(404, 'File not found: ' . $fileRecord->file_path);
    }
}