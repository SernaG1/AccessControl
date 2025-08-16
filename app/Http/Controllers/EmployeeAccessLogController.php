<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeAccessLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAccessLogController extends Controller
{
    public function dashboard()
    {
        $user = null;
        $activeLog = null;

        if (request()->has('numero_documento')) {
            $user = Employee::where('numero_documento', request('numero_documento'))->first();

            if ($user) {
                $activeLog = $user->accessLogs()->whereNull('exit_time')->first();
            }
        }

        $employeesInside = EmployeeAccessLog::whereNull('exit_time')->with('employee')->get();
        return view('incomes.employee.index', compact('employeesInside', 'user', 'activeLog'));
    }

    public function create()
    {
        return view('incomes.employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_documento' => 'required|string|max:20',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:M,F',
            'telefono' => 'nullable|string|max:20',
            'area' => 'nullable|string|max:100',
            'foto_webcam' => 'nullable|string',
        ]);

        $employee = new Employee();
        $employee->fill($request->only([
            'numero_documento',
            'nombres',
            'apellidos',
            'fecha_nacimiento',
            'genero',
            'telefono',
            'area',
        ]));

        if ($request->filled('foto_webcam')) {
            $imageData = str_replace('data:image/jpeg;base64,', '', $request->input('foto_webcam'));
            $imageData = base64_decode($imageData);
            $imageName = 'employee_' . $request->input('numero_documento') . '.jpg';
            \Storage::disk('public')->put('photos/' . $imageName, $imageData);
            $employee->foto_webcam = 'photos/' . $imageName;
        }

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Empleado registrado exitosamente.');
    }

    public function registerEntry($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $activeEntry = $employee->accessLogs()->whereNull('exit_time')->first();

        if ($activeEntry) {
            return redirect()->back()->with('error', 'El empleado ya está registrado como dentro.');
        }

        $log = new EmployeeAccessLog();
        $log->employee_id = $employee->id;
        $log->entry_time = Carbon::now();
        $log->exit_time = null;
        $log->save();

        return redirect()->route('employee.index')->with('success', 'Entrada registrada correctamente.');
    }

    public function registerExit($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $log = $employee->accessLogs()->whereNull('exit_time')->latest()->first();

        if (!$log) {
            return redirect()->back()->with('error', 'No hay entrada activa.');
        }

        $log->update(['exit_time' => now()]);

        return redirect()->route('employee.index')->with('success', 'Salida registrada correctamente.');
    }

    public function getEmployeesInside()
    {
        $employeesInside = EmployeeAccessLog::whereNull('exit_time')->with('employee')->get();
        return view('incomes.employee.index', compact('employeesInside'));
    }
}
