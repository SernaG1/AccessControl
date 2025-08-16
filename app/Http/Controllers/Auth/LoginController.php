<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja la autenticación de admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Intento de login', [
            'username' => $request->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Autenticar solo como Admin
        $admin = Admin::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            Log::info('Login exitoso como admin', ['username' => $request->username]);
            return redirect()->route('home');
        }

        Log::warning('Login fallido', ['username' => $request->username]);
        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput();
    }

    /**
     * Maneja el logout (cerrar sesión)
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Obtener el guard actual del usuario autenticado
     */
    public static function getCurrentGuard()
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        }
        return null;
    }

    /**
     * Verificar si el usuario actual es admin
     */
    public static function isAdmin()
    {
        return Auth::guard('admin')->check();
    }
}
