<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\UsersIncome;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccessLogController extends Controller
{
    public function registerEntry($visitorId)
    {
        \Log::info("registrando para id visitante: {$visitorId} en " . now());

        $visitor = UsersIncome::findOrFail($visitorId);

        // Verificar si ya tiene una entrada sin salida
        $activeEntry = $visitor->accessLogs()->whereNull('exit_time')->first();

        if ($activeEntry) {
            \Log::warning("ID visitante: {$visitorId} Ya ha sido ingresado, hora de ingreso: " . $activeEntry->entry_time);
            return redirect()->back()->with('error', 'El visitante ya está registrado como dentro.');
        }

        // Registrar nueva entrada
        $log = new AccessLog();
        $log->visitor_id = $visitor->id;
        $log->entry_time = Carbon::now();  // Mejor que usar date_create o date()
        $log->exit_time = null;
        $log->save();

        \Log::info("entrada registrada para visitante: {$visitorId} con entrada en: " . $log->entry_time);

        session()->flash('success', 'Entrada registrada correctamente.');
        return redirect()->route('visitor.index');
    }

public function registerExit($visitorId)
{
    \Log::info("Registering exit for visitor ID: {$visitorId} at " . now());

    $visitor = UsersIncome::findOrFail($visitorId);

    $log = $visitor->accessLogs()
        ->whereNull('exit_time')
        ->latest()
        ->first();

    if (!$log) {
        \Log::warning("Sin entrada activa.");
        session()->flash('error', 'No hay entrada activa.');
        return redirect()->route('visitor.index');
    }

    // DEBUG antes
    \Log::debug('ANTES de actualizar', [
        'entry_time' => $log->entry_time,
        'exit_time' => $log->exit_time,
        'dirty' => $log->getDirty(),
    ]);

    //$log->exit_time = now(); // Solo actualiza exit_time
    //$log->save(); // O usa saveQuietly() si hay eventos

    $log->update(['exit_time' => now()]);


    // DEBUG después
    \Log::debug('DESPUÉS de actualizar', [
        'entry_time' => $log->entry_time,
        'exit_time' => $log->exit_time,
        'dirty' => $log->getDirty(),
    ]);

    session()->flash('success', 'Salida registrada correctamente.');
    return redirect()->route('visitor.index');
}


    public function getVisitorsInside()
    {
        $visitorsInside = AccessLog::whereNull('exit_time')->with('visitor')->get();
        return view('visitor.index', compact('visitorsInside'));
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

        $visitorsInside = AccessLog::whereNull('exit_time')->with('visitor')->get();
        return view('incomes.visitor.index', compact('visitorsInside', 'user', 'activeLog'));
    }

    // Métodos vacíos del resource controller (puedes eliminarlos si no los usas)
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(AccessLog $accessLog) {}
    public function edit(AccessLog $accessLog) {}
    public function update(Request $request, AccessLog $accessLog) {}
    public function destroy(AccessLog $accessLog) {}
}
