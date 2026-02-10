<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function getFile($namaFile)
    {
        $file = storage_path('app/public/file/materi/' . $namaFile);

        return response()->download($file, $namaFile);
    }

    public function getFileUser($namaFile)
    {
        // $file = url('/file/tugas/user/' . $namaFile);
        $file = storage_path('app/public/file/tugas/user/' . $namaFile);

        return response()->download($file, $namaFile);
    }

    public function getFileTugas($namaFile)
    {
        // $file = url('/file/tugas/' . $namaFile);
        $file = storage_path('app/public/file/tugas/' . $namaFile);

        return response()->download($file, $namaFile);
    }

    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                // Store in public storage
                $path = $file->storeAs('public/editor-images', $filename);
                
                // Return the public URL
                $url = asset('storage/editor-images/' . $filename);
                
                return response()->json([
                    'success' => true,
                    'url' => $url
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file provided'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }
}
