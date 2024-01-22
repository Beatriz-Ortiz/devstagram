<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct() {
        // Solo podrá realizar acciones el usuario autenticado
        $this->middleware('auth');
    }

    public function index() {
        return view('perfil.index');
    }

    public function store(Request $request) {
        // Modify request username
        $request->request->add(['username' => Str::slug($request->username)]);

        // Si hay mas de tres reglas en un campo mejor colocarlos en un array con los elementos
        // separados por comas
        $this->validate($request, [
            'username' => [
                'required',
                // Tendrá en cuenta si estas proporcionando tu propio username
                'unique:users,username,'.auth()->user()->id,
                'min:3',
                'max:20',
                'not_in:twitter,editar-perfil'
            ]
        ]);

        if ($request->imagen) {
            $imagen = $request->file('imagen');

            // Create image name
            $nombreImagen = Str::uuid() . "." . $imagen->extension();
            $imagenServidor = Image::make($imagen);

            // Format image size
            $imagenServidor->fit(1000, 1000);

            // Crear una ruta donde guardar la nueva imagen y guardarla
            $imagenPath = public_path('perfiles'). "/" . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        // Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        // Redireccionar al usuario, usando sus nuevos datos
        return redirect()->route('posts.index', $usuario->username);
    }
}
