<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Empleado;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'DNI' => 'required|numeric',
            'password' => 'required'
        ]);

        $empleado = Empleado::where('DNI', $request->DNI)->first();

        if ($empleado && Hash::check($request->password, $empleado->ContrasenaEmpleado)) {
            Auth::login($empleado); // Usa el modelo autenticable Empleado
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['DNI' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
