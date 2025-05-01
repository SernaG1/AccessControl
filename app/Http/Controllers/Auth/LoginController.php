<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Vista donde estará el formulario de login
    }

    /**
     * Maneja la autenticación del admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $admin = Admin::where('username', $request->username)->first();
    
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Usamos el guard 'admin' para la autenticación
            Auth::guard('admin')->login($admin); // <-- Asegúrate de usar el guard adecuado
    
            return redirect()->route('home');
        }
    
        return back()->with('error', 'Usuario o contraseña incorrectos.');
    }
    /**
     * Maneja el logout (cerrar sesión).
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // <-- Usar el guard 'admin' en el logout
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login');
    }
}
