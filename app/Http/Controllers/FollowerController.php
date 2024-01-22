<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(User $user) {
        // Guardar datos en una tabla con referencias a una
        // misma tabla (users en este caso)
        $user->followers()->attach(auth()->user()->id);
        return back();
    }

    public function destroy(Request $request, User $user) {
        $user->followers()->detach(auth()->user()->id);
        return back();
    }
}
