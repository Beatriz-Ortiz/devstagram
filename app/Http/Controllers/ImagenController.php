<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request) {
        $imagen = $request->file('file');

        // Create image name
        $nombreImagen = Str::uuid() . "." . $imagen->extension();
        $imagenServidor = Image::make($imagen);

        // Format image size
        $imagenServidor->fit(1000, 1000);

        // Crear una ruta donde guardar la nueva imagen y guardarla
        $imagenPath = public_path('uploads'). "/" . $nombreImagen;
        $imagenServidor->save($imagenPath);

        return response()->json( ['imagen' => $nombreImagen ]);
    }
}
