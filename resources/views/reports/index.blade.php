@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Generar Reporte de Logs</h2>

    <form method="GET" action="{{ route('reports.search') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="user_type" class="form-label">Tipo de Usuario</label>
                <select name="user_type" id="user_type" class="form-select" required>
                    <option value="" disabled selected>Selecciona</option>
                    <option value="visitor" {{ request('user_type') == 'visitor' ? 'selected' : '' }}>Visitante</option>
                    <option value="employee" {{ request('user_type') == 'employee' ? 'selected' : '' }}>Empleado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="numero_documento" class="form-label">Número de Documento</label>
                <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ request('numero_documento') }}">
            </div>
            <div class="col-md-3">
                <label for="nombre" class="form-label">Nombre de Usuario</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ request('nombre') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3">
                <label for="hora_desde" class="form-label">Hora Desde</label>
                <input type="time" name="hora_desde" id="hora_desde" class="form-control" value="{{ request('hora_desde') }}">
            </div>
            <div class="col-md-3">
                <label for="hora_hasta" class="form-label">Hora Hasta</label>
                <input type="time" name="hora_hasta" id="hora_hasta" class="form-control" value="{{ request('hora_hasta') }}">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    @if(isset($logs) && $logs->count())
    <button id="exportBtn" class="btn btn-success mt-3">Exportar en Excel</button>
    @endif

    @if(isset($logs))
        <hr>
        <h4>Resultados</h4>
        @if($logs->count())
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Área</th>
                        <th>Hora de Entrada</th>
                        <th>Hora de Salida</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->visitor->numero_documento ?? $log->employee->numero_documento ?? '' }}</td>
                            <td>
                                {{ $log->visitor->nombres ?? $log->employee->nombres ?? '' }}
                                {{ $log->visitor->apellidos ?? $log->employee->apellidos ?? '' }}
                            </td>
                            <td>{{ $log->visitor->area ?? $log->employee->area ?? '' }}</td>
                            <td>{{ $log->entry_time ? $log->entry_time->format('d/m/Y H:i:s') : '' }}</td>
                            <td>{{ $log->exit_time ? $log->exit_time->format('d/m/Y H:i:s') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
        @else
            <p>No se encontraron registros con los filtros aplicados.</p>
        @endif
    @endif
</div>

@vite(['resources/js/app.js'])

<script>
    document.getElementById('exportBtn').addEventListener('click', () => {
        const filters = {
            user_type: document.getElementById('user_type').value,
            numero_documento: document.getElementById('numero_documento').value,
            nombre: document.getElementById('nombre').value,
            fecha_desde: document.getElementById('fecha_desde').value,
            fecha_hasta: document.getElementById('fecha_hasta').value,
            hora_desde: document.getElementById('hora_desde').value,
            hora_hasta: document.getElementById('hora_hasta').value,
        };
    });
</script>

@endsection
