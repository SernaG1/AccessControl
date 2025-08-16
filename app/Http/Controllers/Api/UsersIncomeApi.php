<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersIncome;

class UsersIncomeApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(UsersIncome::all());
    }

    /**
     * Parse scanner input string and return parsed fields.
     */
    public function parseScannerString(Request $request)
    {
        $request->validate([
            'scanner_string' => 'required|string',
        ]);

        $scannerString = $request->input('scanner_string');

        // Example input: "1002547605 SERNA GARZON JUAN MANUEL M 19990515"
        // Parsing logic: split by spaces, assign fields accordingly

        $parts = preg_split('/\s+/', trim($scannerString));

        if (count($parts) < 6) {
            return response()->json(['error' => 'Invalid scanner string format'], 422);
        }

        $numero_documento = ltrim($parts[0], '0');
        $genero = $parts[count($parts) - 2];
        $fecha_nacimiento_raw = $parts[count($parts) - 1];

        // Parse birthdate from YYYYMMDD to YYYY-MM-DD
        $fecha_nacimiento = null;
        if (preg_match('/^\d{8}$/', $fecha_nacimiento_raw)) {
            $fecha_nacimiento = substr($fecha_nacimiento_raw, 0, 4) . '-' .
                               substr($fecha_nacimiento_raw, 4, 2) . '-' .
                               substr($fecha_nacimiento_raw, 6, 2);
        }

        // Extract names and surnames (middle parts)
        $middleParts = array_slice($parts, 1, count($parts) - 3);

      
        if (count($middleParts) < 3) {
            return response()->json(['error' => 'Invalid name format in scanner string'], 422);
        }

        $apellidos = $middleParts[0] . ' ' . $middleParts[1];
        $nombres = implode(' ', array_slice($middleParts, 2));

        return response()->json([
            'numero_documento' => $numero_documento,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'genero' => $genero,
            'fecha_nacimiento' => $fecha_nacimiento,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero_documento' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string|max:50',
            'area' => 'required|string|max:255',
            'rh' => 'required|string|max:10',
            'foto_webcam' => 'nullable|string',
        ]);

        $userIncome = UsersIncome::create($validatedData);

        return response()->json($userIncome, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userIncome = UsersIncome::find($id);
        if (!$userIncome) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json($userIncome);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userIncome = UsersIncome::find($id);
        if (!$userIncome) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $validatedData = $request->validate([
            'numero_documento' => 'sometimes|required|string|max:255',
            'nombres' => 'sometimes|required|string|max:255',
            'apellidos' => 'sometimes|required|string|max:255',
            'fecha_nacimiento' => 'sometimes|required|date',
            'genero' => 'sometimes|required|string|max:50',
            'area' => 'sometimes|required|string|max:255',
            'rh' => 'sometimes|required|string|max:10',
            'foto_webcam' => 'nullable|string',
        ]);

        $userIncome->update($validatedData);

        return response()->json($userIncome);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userIncome = UsersIncome::find($id);
        if (!$userIncome) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $userIncome->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
