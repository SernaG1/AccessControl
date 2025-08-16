<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLog;
use App\Models\EmployeeAccessLog;
use App\Models\UsersIncome;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:visitor,employee',
            'numero_documento' => 'nullable|string',
            'nombre' => 'nullable|string',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'hora_desde' => 'nullable',
            'hora_hasta' => 'nullable',
        ]);

        $userType = $request->input('user_type');
        $query = null;

        if ($userType === 'visitor') {
            $query = AccessLog::with('visitor');
            if ($request->filled('numero_documento')) {
                $query->whereHas('visitor', function ($q) use ($request) {
                    $q->where('numero_documento', 'like', '%' . $request->numero_documento . '%');
                });
            }
            if ($request->filled('nombre')) {
                $query->whereHas('visitor', function ($q) use ($request) {
                    $q->where('nombres', 'like', '%' . $request->nombre . '%')
                      ->orWhere('apellidos', 'like', '%' . $request->nombre . '%');
                });
            }
        } else {
            $query = EmployeeAccessLog::with('employee');
            if ($request->filled('numero_documento')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('numero_documento', 'like', '%' . $request->numero_documento . '%');
                });
            }
            if ($request->filled('nombre')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('nombres', 'like', '%' . $request->nombre . '%')
                      ->orWhere('apellidos', 'like', '%' . $request->nombre . '%');
                });
            }
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('entry_time', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('entry_time', '<=', $request->fecha_hasta);
        }
        if ($request->filled('hora_desde')) {
            $query->whereTime('entry_time', '>=', $request->hora_desde);
        }
        if ($request->filled('hora_hasta')) {
            $query->whereTime('entry_time', '<=', $request->hora_hasta);
        }

        $logs = $query->orderBy('entry_time', 'desc')->simplePaginate(10)->appends($request->all());

        return view('reports.index', compact('logs'));
    }

    public function export(Request $request)
    {
        // This method is deprecated and replaced by JS export functionality.
        abort(404);
    }

    public function exportData(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:visitor,employee',
            'numero_documento' => 'nullable|string',
            'nombre' => 'nullable|string',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date',
            'hora_desde' => 'nullable',
            'hora_hasta' => 'nullable',
        ]);

        $userType = $request->input('user_type');
        $query = null;

        if ($userType === 'visitor') {
            $query = AccessLog::with('visitor');
            if ($request->filled('numero_documento')) {
                $query->whereHas('visitor', function ($q) use ($request) {
                    $q->where('numero_documento', 'like', '%' . $request->numero_documento . '%');
                });
            }
            if ($request->filled('nombre')) {
                $query->whereHas('visitor', function ($q) use ($request) {
                    $q->where('nombres', 'like', '%' . $request->nombre . '%')
                      ->orWhere('apellidos', 'like', '%' . $request->nombre . '%');
                });
            }
        } else {
            $query = EmployeeAccessLog::with('employee');
            if ($request->filled('numero_documento')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('numero_documento', 'like', '%' . $request->numero_documento . '%');
                });
            }
            if ($request->filled('nombre')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('nombres', 'like', '%' . $request->nombre . '%')
                      ->orWhere('apellidos', 'like', '%' . $request->nombre . '%');
                });
            }
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('entry_time', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('entry_time', '<=', $request->fecha_hasta);
        }
        if ($request->filled('hora_desde')) {
            $query->whereTime('entry_time', '>=', $request->hora_desde);
        }
        if ($request->filled('hora_hasta')) {
            $query->whereTime('entry_time', '<=', $request->hora_hasta);
        }

        $logs = $query->orderBy('entry_time', 'desc')->get();

        $exportData = $logs->map(function ($log) use ($userType) {
            if ($userType === 'visitor') {
                return [
                    'Documento' => $log->visitor->numero_documento ?? '',
                    'Nombre' => ($log->visitor->nombres ?? '') . ' ' . ($log->visitor->apellidos ?? ''),
                    'Área' => $log->visitor->area ?? '',
                    'Hora de Entrada' => $log->entry_time ? $log->entry_time->format('Y-m-d H:i:s') : '',
                    'Hora de Salida' => $log->exit_time ? $log->exit_time->format('Y-m-d H:i:s') : '',
                ];
            } else {
                return [
                    'Documento' => $log->employee->numero_documento ?? '',
                    'Nombre' => ($log->employee->nombres ?? '') . ' ' . ($log->employee->apellidos ?? ''),
                    'Área' => $log->employee->area ?? '',
                    'Hora de Entrada' => $log->entry_time ? $log->entry_time->format('Y-m-d H:i:s') : '',
                    'Hora de Salida' => $log->exit_time ? $log->exit_time->format('Y-m-d H:i:s') : '',
                ];
            }
        });

        return response()->json($exportData);
    }
}
