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
