<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

   public function index()
    {
        // Verifica si el admin está autenticado usando el guard 'admin'
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user(); // Obtener el administrador autenticado

            // Obtener las actividades recientes de AccessLog con información del visitante
            $recentActivities = \App\Models\AccessLog::with('visitor')
                ->orderByDesc('entry_time')
                ->orderByDesc('exit_time')
                ->limit(10)
                ->get();

            // Muestra el valor del atributo 'username' del administrador junto con las actividades recientes
            return view('home', ['admin' => $admin, 'recentActivities' => $recentActivities]);
        } else {
            return redirect()->route('login'); // Redirige si no está autenticado
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Home $home)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Home $home)
    {
        //
    }
}
