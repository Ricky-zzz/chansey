<?php

namespace App\Services;

use App\Models\Admission;
use Illuminate\Support\Facades\DB;

class AdmissionNumberGenerator
{
    /**
     * Generate a unique admission number with race condition protection.
     * Format: ADM-YYYYMMDD-XXX (e.g., ADM-20260106-001)
     *
     * @param \DateTime|string|null $date The date to use for the admission number (defaults to today)
     * @return string The generated admission number
     * @throws \Exception If unable to generate a unique number after max retries
     */
    public function generate($date = null): string
    {
        $dateStr = $date ? date('Ymd', strtotime($date)) : date('Ymd');
        $prefix = "ADM-{$dateStr}-";

        return $this->executeWithRetry(function () use ($prefix) {
            return DB::transaction(function () use ($prefix) {
                // Lock the admissions table for the duration of this transaction
                // to prevent race conditions when multiple admissions are created simultaneously
                $lastAdmission = Admission::where('admission_number', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->orderByRaw('CAST(SUBSTRING(admission_number, -3) AS UNSIGNED) DESC')
                    ->first();

                if ($lastAdmission) {
                    $lastNumber = $this->extractSequenceNumber($lastAdmission->admission_number);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                // Support up to 999 admissions per day, extend to 4 digits if needed
                $digits = $nextNumber > 999 ? 4 : 3;
                
                return $prefix . str_pad($nextNumber, $digits, '0', STR_PAD_LEFT);
            });
        });
    }

    /**
     * Execute a callback with retry logic for database deadlocks.
     *
     * @param callable $callback
     * @param int $maxRetries
     * @return mixed
     * @throws \Exception
     */
    protected function executeWithRetry(callable $callback, int $maxRetries = 5)
    {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            $attempt++;

            try {
                return $callback();
            } catch (\Illuminate\Database\QueryException $e) {
                if ($this->isRetryableException($e) && $attempt < $maxRetries) {
                    usleep(100000 * $attempt); // Exponential backoff: 100ms, 200ms, 300ms...
                    continue;
                }
                throw $e;
            }
        }

        throw new \Exception("Failed after {$maxRetries} attempts");
    }

    /**
     * Extract the sequence number from an admission number.
     *
     * @param string $admissionNumber
     * @return int
     */
    protected function extractSequenceNumber(string $admissionNumber): int
    {
        // Handle both 3-digit and 4-digit sequences
        $parts = explode('-', $admissionNumber);
        $sequence = end($parts);
        
        return (int) $sequence;
    }

    /**
     * Check if the exception is retryable (deadlock or lock timeout).
     *
     * @param \Illuminate\Database\QueryException $e
     * @return bool
     */
    protected function isRetryableException(\Illuminate\Database\QueryException $e): bool
    {
        $retryableErrors = [
            1205, // Lock wait timeout exceeded
            1213, // Deadlock found when trying to get lock
            40001, // Serialization failure (PostgreSQL)
        ];

        return in_array($e->errorInfo[1] ?? 0, $retryableErrors);
    }

    /**
     * Validate an admission number format.
     *
     * @param string $admissionNumber
     * @return bool
     */
    public function isValid(string $admissionNumber): bool
    {
        return (bool) preg_match('/^ADM-\d{8}-\d{3,4}$/', $admissionNumber);
    }
}
