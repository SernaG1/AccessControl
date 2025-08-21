<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('incomes.web_user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.search')->with('success', 'Administrador creado exitosamente.');
    }


    public function getAllEmployees()
    {
    $web_users = Admin::select('id','username')->paginate(5);
     return view('incomes.web_user.search', compact('web_users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('incomes.web_user.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        // Actualizar nueva contraseña
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.search')->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.search')->with('success', 'Administrador eliminado exitosamente.');
    }
}
