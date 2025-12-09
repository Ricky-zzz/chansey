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

        $privatePath = storage_path('app/private/' . $fileRecord->file_path);
        $publicPath = storage_path('app/' . $fileRecord->file_path);

        if (file_exists($privatePath)) {
            return response()->file($privatePath);
        } elseif (file_exists($publicPath)) {
            return response()->file($publicPath);
        }

        abort(404, 'File not found on server.');
    }
}