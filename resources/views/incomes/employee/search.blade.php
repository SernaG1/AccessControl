@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Listado de Empleados</h2>

    <form method="GET" action="{{ route('employee.searchUser') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre o documento" value="{{ request('nombre') }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    @if($employees->count())
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre Completo</th>
                <th>Área</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->numero_documento }}</td>
                <td>{{ $employee->nombres }} {{ $employee->apellidos }}</td>
                <td>{{ $employee->area }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#employeeModal{{ $employee->id }}">
                        Ver Información
                    </button>

                    <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este empleado?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>

            <!-- Modal de credencial para cada empleado -->
            <div class="modal fade" id="employeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="employeeModalLabel{{ $employee->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content text-center">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="employeeModalLabel{{ $employee->id }}">Credencial de {{ $employee->nombres }} {{ $employee->apellidos }}</h5>
                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            @if($employee->foto_webcam)
                                <img src="{{ asset('storage/' . $employee->foto_webcam) }}" alt="Foto de {{ $employee->nombres }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                            @else
                                <div class="text-muted">Sin Foto</div>
                            @endif
                            <p><strong>Cédula:</strong> {{ $employee->numero_documento }}</p>
                            <p><strong>Nombre:</strong> {{ $employee->nombres }} {{ $employee->apellidos }}</p>
                            <p><strong>Área:</strong> {{ $employee->area }}</p>
                            <p><strong>RH:</strong> {{ $employee->rh }}</p>
                            <p><strong>Género:</strong> {{ $employee->genero }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $employee->fecha_nacimiento }}</p>
                            <p><strong>Teléfono:</strong> {{ $employee->telefono }}</p>
                            <p><strong>Contacto Emergencia:</strong> {{ $employee->nombre_contacto_emergencia }}</p>
                            <p><strong>Teléfono Contacto Emergencia:</strong> {{ $employee->telefono_contacto_emergencia }}</p>
                            <p><strong>Dirección:</strong> {{ $employee->direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $employees->links() }}
    </div>
    @else
        <p class="text-center">No hay empleados registrados aún.</p>
    @endif
</div>
@endsection
