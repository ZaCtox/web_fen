<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryTestController extends Controller
{
    public function form()
    {
        return view('cloudinary_test');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|max:2048',
        ]);

        try {
            $uploadedFile = $request->file('imagen')->getRealPath();

            $cloudinaryUpload = (new UploadApi())->upload($uploadedFile, [
                'folder' => 'pruebas'
            ]);

            return back()->with('success', 'Imagen subida correctamente')
                         ->with('url', $cloudinaryUpload['secure_url']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al subir: ' . $e->getMessage()]);
        }
    }
}
