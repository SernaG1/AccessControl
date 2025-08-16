@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div>   
        <h2>Bienvenido!</h2>
        <p class="lead">Esta es la página principal del sistema de control de ingreso.</p>
</div>
    <div class="d-flex justify-content-between">
        <div class="border p-3 me-3 flex-fill">
            <h3>Opciones empleados</h3>
            <a href="{{ route('employee.index') }}" class="btn btn-primary mb-3 w-100">Ver Empleados Dentro</a>
            <a href="{{ route('employee.create') }}" class="btn btn-secondary mb-3 w-100">Registrar Nuevo Empleado</a>
            <a href="{{ route('employee.search') }}" class="btn btn-info mb-3 w-100">Buscar Empleado</a>
        </div>
        <div class="border p-3 ms-3 flex-fill">
            <h3>Opciones visitantes</h3>
            <a href="{{ route('visitor.index') }}" class="btn btn-primary mb-3 w-100">Ver Visitantes Dentro</a>
            <a href="{{ route('incomes.create') }}" class="btn btn-secondary mb-3 w-100">Registrar Nuevo Visitante</a>
            <a href="{{ route('incomes.search') }}" class="btn btn-info mb-3 w-100">Buscar Visitante</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3>Resumen de Actividad</h3>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visitantes Recientes</h5>
                    <p class="card-text">Aquí puedes ver las entradas y salidas más recientes de los visitantes.</p>

                    @if($recentActivities->count(5))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Área</th>
                                        <th>Hora de Entrada</th>
                                        <th>Hora de Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $log)
                                        <tr>
                                            <td>{{ $log->visitor->numero_documento }}</td>
                                            <td>{{ $log->visitor->nombres }} {{ $log->visitor->apellidos }}</td>
                                            <td>{{ $log->visitor->area }}</td>
                                            <td>{{ $log->entry_time ? $log->entry_time->format('d/m/Y H:i:s') : '---' }}</td>
                                            <td>{{ $log->exit_time ? $log->exit_time->format('d/m/Y H:i:s') : '---' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay actividad reciente registrada.</p>
                    @endif

                    <h5 class="card-title mt-4">Empleados Recientes</h5>
                    <p class="card-text">Aquí puedes ver las entradas y salidas más recientes de los empleados.</p>

                    @if($recentEmployeeActivities->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Área</th>
                                        <th>Hora de Entrada</th>
                                        <th>Hora de Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEmployeeActivities as $log)
                                        <tr>
                                            <td>{{ $log->employee->numero_documento }}</td>
                                            <td>{{ $log->employee->nombres }} {{ $log->employee->apellidos }}</td>
                                            <td>{{ $log->employee->area }}</td>
                                            <td>{{ $log->entry_time ? $log->entry_time->format('d/m/Y H:i:s') : '---' }}</td>
                                            <td>{{ $log->exit_time ? $log->exit_time->format('d/m/Y H:i:s') : '---' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay actividad reciente registrada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
