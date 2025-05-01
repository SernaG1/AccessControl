<?php

namespace App\Http\Controllers;

use App\Models\UsersIncome;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class UsersIncomeController extends Controller
{
    /**
     * Mostrar visitantes dentro de la sede (incomes.index).
     */
    public function index(Request $request)
    {
        // Validar el número de cédula
        $validated = $request->validate([
            'numero_documento' => 'required|numeric|digits_between:6,12',
        ]);

        // Intentar encontrar al visitante con la cédula
        $user = UsersIncome::where('numero_documento', $request->numero_documento)->first();

        // Obtener visitantes actualmente dentro
        $visitorsInside = AccessLog::whereNull('exit_time')->get();

        // Si el visitante existe, verificar si está registrado en el log
        if ($user) {
            $activeLog = AccessLog::where('visitor_id', $user->id)
                ->whereNull('exit_time')
                ->first();

            return view('incomes.index', compact('user', 'activeLog', 'visitorsInside'));
        } else {
            // Si no existe el visitante, retornar la vista con un mensaje
            return view('incomes.index', compact('user', 'visitorsInside'));
        }
    }

    /**
     * Listar todos los visitantes (incomes.search).
     */ 
    public function getAllUsers()
    {
        $visitors = UsersIncome::orderBy('nombres')->paginate(10);
        return view('incomes.search', compact('visitors'));
    }

    /**
     * Buscar por cédula y listar visitantes dentro (incomes.search).
     */
    public function search(Request $request)
    {
        // Validar el número de cédula
        $validated = $request->validate([
            'numero_documento' => 'required|numeric|digits_between:6,12',
        ]);

        // Buscar por número de documento
        $user = UsersIncome::where('numero_documento', $request->numero_documento)->first();
        $activeLog = null;

        // Si el visitante existe, verificar si está adentro
        if ($user) {
            $activeLog = AccessLog::where('visitor_id', $user->id)
                ->whereNull('exit_time')
                ->first();
        }

        // Obtener visitantes actualmente dentro
        $visitorsInside = AccessLog::whereNull('exit_time')->with('visitor')->get();

        // Retornar la vista con el visitante encontrado y los visitantes dentro
        return view('incomes.index', compact('user', 'visitorsInside', 'activeLog'));
    }

    /**
     * Formulario para registrar visitante.
     */
    public function create()
    {
        return view('incomes.create');
    }

    /**
     * Guardar nuevo visitante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_documento' => 'required|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:M,F',
            'area' => 'nullable|string|max:100',
            'foto_webcam' => 'nullable|string',
            'rh' => 'nullable|string|max:5',
        ]);

        $income = new UsersIncome();
        $income->numero_documento = $request->input('numero_documento');
        $income->nombres = $request->input('nombres');
        $income->apellidos = $request->input('apellidos');
        $income->fecha_nacimiento = $request->input('fecha_nacimiento');
        $income->genero = $request->input('genero');
        $income->area = $request->input('area');
        $income->rh = $request->input('rh');

        // Si se incluye una foto, procesarla
        if ($request->filled('foto_webcam')) {
            $imageData = $request->input('foto_webcam');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = base64_decode($imageData);

            $imageName = 'visitor_' . $request->input('numero_documento') . '.jpg';
            \Storage::disk('public')->put('photos/' . $imageName, $imageData);

            $income->foto_webcam = 'photos/' . $imageName;
        }

        $income->save();

        return redirect()->route('incomes.index')->with('success', 'Ingreso registrado exitosamente.');
    }

    /**
     * Mostrar formulario de edición de visitante.
     */
    public function edit(UsersIncome $usersIncome)
    {
        return view('incomes.edit', compact('usersIncome'));
    }
    
    /**
     * Actualizar visitante.
     */
    public function update(Request $request, UsersIncome $usersIncome)
    {
        $request->validate([
            'numero_documento' => 'required|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:M,F',
            'area' => 'nullable|string|max:100',
            'rh' => 'nullable|string|max:5',
            'foto_webcam' => 'nullable|string',
        ]);

        $usersIncome->numero_documento = $request->input('numero_documento');
        $usersIncome->nombres = $request->input('nombres');
        $usersIncome->apellidos = $request->input('apellidos');
        $usersIncome->fecha_nacimiento = $request->input('fecha_nacimiento');
        $usersIncome->genero = $request->input('genero');
        $usersIncome->area = $request->input('area');
        $usersIncome->rh = $request->input('rh');

        // Si se incluye una foto, procesarla
        if ($request->filled('foto_webcam')) {
            $imageData = str_replace('data:image/jpeg;base64,', '', $request->input('foto_webcam'));
            $imageData = base64_decode($imageData);
            $imageName = 'visitor_' . $request->input('numero_documento') . '.jpg';
            \Storage::disk('public')->put('photos/' . $imageName, $imageData);
            $usersIncome->foto_webcam = 'photos/' . $imageName;
        }

        $usersIncome->save();

        return redirect()->route('incomes.index')->with('success', 'Datos del visitante actualizados correctamente.');
    }

    /**
     * Eliminar visitante.
     */
    public function destroy(UsersIncome $usersIncome)
    {
        $usersIncome->delete();

        return redirect()->route('incomes.index')->with('success', 'Visitante eliminado exitosamente.');
    }
}
