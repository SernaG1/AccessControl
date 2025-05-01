<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\UsersIncome;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{

    
    public function registerEntry($visitorId)
    {
    $visitor = UsersIncome::findOrFail($visitorId);

    // Verificar si ya tiene una entrada sin salida
    $activeEntry = $visitor->accessLogs()->whereNull('exit_time')->first();

    if ($activeEntry) {
        return redirect()->back()->with('error', 'El visitante ya está registrado como dentro.');
    }

    // Registrar nueva entrada
    $log = new AccessLog();
    $log->visitor_id = $visitor->id;
    $log->entry_time = now();
    $log->save();
        session()->flash('success', 'Entrada registrada correctamente.');
        return redirect()->route('incomes.index');
    }

    /**
     * Registrar la salida de un visitante.
     */
    public function registerExit($visitorId)
    {
        $visitor = UsersIncome::findOrFail($visitorId);

        // Verifica si tiene un registro de entrada activo
        $log = $visitor->accessLogs()
            ->whereNull('exit_time')
            ->latest()
            ->first();
    
        if (!$log) {
            // No tiene registro activo, no se puede registrar salida
            session()->flash('error', 'El visitante no tiene una entrada activa.');
            return redirect()->route('incomes.index');
        }
    
        // Registrar la hora de salida
        $log->exit_time = now();
        $log->save();
    
        session()->flash('success', 'Salida registrada correctamente.');
        return redirect()->route('incomes.index');
    }

    /**
     * Obtener los visitantes actualmente dentro de la sede.
     */
    public function getVisitorsInside()
    {
    // Obtener los visitantes que tienen una entrada activa (sin salida registrada)
    $visitorsInside = AccessLog::whereNull('exit_time')->with('visitor')->get();

    return view('incomes.index', compact('visitorsInside'));
    }

    public function dashboard()
    {
        $user = null;
        $activeLog = null;

        if (request()->has('numero_documento')) {
            $user = UsersIncome::where('numero_documento', request('numero_documento'))->first();

            if ($user) {
                $activeLog = $user->accessLogs()->whereNull('exit_time')->first();
            }
        }

        // Obtener los visitantes que tienen una entrada activa (sin salida registrada)
        $visitorsInside = AccessLog::whereNull('exit_time')->with('visitor')->get();

        return view('incomes.index', compact('visitorsInside', 'user', 'activeLog'));
    }

    public function index()
    {
        //
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
    public function show(AccessLog $accessLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessLog $accessLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessLog $accessLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessLog $accessLog)
    {
        //
    }
}
