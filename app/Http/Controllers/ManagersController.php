<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagersController extends Controller
{
    /**
     * Display a form to create new web users (managers)
     */
    public function create()
    {
        return view('managers.create');
    }

    /**
     * Store a new web user (manager)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Manager::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Usuario web creado exitosamente.');
    }
}
