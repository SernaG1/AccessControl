@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Bienvenido, {{ $admin->username }}!</h2>
            <p class="lead">Esta es la página principal del sistema de control de ingreso.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('incomes.index') }}" class="btn btn-primary mb-3">Ver Visitantes Dentro</a>
            <a href="{{ route('incomes.create') }}" class="btn btn-secondary mb-3">Registrar Nuevo Visitante</a>
            <a href="{{ route('incomes.search') }}" class="btn btn-info mb-3">Buscar Visitante</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Resumen de Actividad</h3>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visitantes Recientes</h5>
                    <p class="card-text">Aquí puedes ver las entradas y salidas más recientes de los visitantes.</p>

                    @if($recentActivities->count())
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
                                            <td>{{ $log->entry_time ?? '---' }}</td>
                                            <td>{{ $log->exit_time ?? '---' }}</td>
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
