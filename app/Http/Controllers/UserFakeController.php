<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserFakeController extends Controller
{
    public function generar(Request $request)
    {
        $cantidad = $request->input('cantidad', 10); // valor por defecto = 10
        User::factory()->count($cantidad)->create();

        return redirect()->back()->with('success', "$cantidad usuarios falsos generados.");
    }
}

