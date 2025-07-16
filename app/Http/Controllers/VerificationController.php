<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function form()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $user = Auth::user();

        if ($user->verification_code == $request->code) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Correo verificado correctamente.');
        }

        return back()->withErrors(['code' => 'Código incorrecto.']);
    }
}
