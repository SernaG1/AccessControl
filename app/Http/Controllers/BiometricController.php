<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;
use App\Models\UsersIncome;
use App\Models\EmployeeFingerprint;
use App\Models\VisitorFingerprint;

class BiometricController extends Controller
{
    protected $exeBaseUrl = 'http://localhost:5000';

    /**
     * Captura la huella para enrolamiento
     */
    public function capture(Request $request)
    {
        try {
            $response = Http::post("{$this->exeBaseUrl}/capture/", $request->all());

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'status' => 'success',
                    'huella' => $data['fingerprint_template'] ?? null
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Error en la captura de huella',
            ], $response->status());

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Excepción capturando huella: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Valida la huella y busca al usuario (empleado o visitante)
     */
    public function identify(Request $request)
    {
        $source = $request->input('source');

        if (!in_array($source, ['employee', 'visitor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro "source" inválido.'
            ], 400);
        }

        // Selección de modelo y campo clave según el source
        if ($source === 'employee') {
            $model = EmployeeFingerprint::class;
            $keyField = 'employee_id';
            $userModel = Employee::class;
        } else {
            $model = VisitorFingerprint::class;
            $keyField = 'visitor_id';
            $userModel = UsersIncome::class;
        }

        // Obtener huellas registradas
        $records = $model::select($keyField, 'fingerprint_template', 'finger_type')
            ->whereNotNull('fingerprint_template')
            ->get();

        if ($records->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay huellas registradas en la base de datos.'
            ], 404);
        }

        // Formato que el .exe espera
        $templates = $records->map(function ($item) use ($keyField) {
            return [
                'person_id' => $item->{$keyField},
                'finger_type' => $item->finger_type,
                'fingerprint_template' => $item->fingerprint_template
            ];
        })->values()->toArray();

        if (empty($templates)) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo generar el array de huellas.'
            ], 500);
        }

        try {
            // Enviar al ejecutable
            $response = Http::timeout(20)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->withBody(json_encode(['templates' => $templates]), 'application/json')
                ->post("{$this->exeBaseUrl}/identify/");

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al comunicarse con el servicio biométrico.',
                    'error'   => $response->body()
                ], $response->status());
            }

            $data = $response->json();

            if (!isset($data['match']) || $data['match'] !== true || !isset($data['person_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró coincidencia en el servicio biométrico.'
                ], 404);
            }

            // Buscar el usuario real basado en el ID devuelto
            $matchedUser = $userModel::find($data['person_id']);

            if (!$matchedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado en la base de datos.'
                ], 404);
            }

            // Verificar si tiene un registro activo
            $activeLog = null;
            if ($source === 'employee') {
                $activeLog = \App\Models\EmployeeAccessLog::where('employee_id', $matchedUser->id)
                    ->whereNull('exit_time')
                    ->latest()
                    ->first();
            }

            return response()->json([
                'success' => true,
                'matched_user' => [
                    'id' => $matchedUser->id,
                    'numero_documento' => $matchedUser->numero_documento,
                    'nombres' => $matchedUser->nombres,
                    'apellidos' => $matchedUser->apellidos,
                    'area' => $matchedUser->area,
                    'rh' => $matchedUser->rh,
                    'genero' => $matchedUser->genero,
                    'fecha_nacimiento' => $matchedUser->fecha_nacimiento,
                    'foto_webcam' => $matchedUser->foto_webcam,
                    'active_log' => $activeLog ? true : false
                ]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la identificación: ' . $e->getMessage()
            ], 500);
        }
    }
}
