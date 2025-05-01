@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Buscar Visitante</h2>

    <!-- Formulario de búsqueda -->
    <form id="search-form" method="GET" action="{{ route('incomes.index') }}">
        <div class="mb-3">
            <label for="numero_documento" class="form-label">Número de Cédula</label>
            <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento', request('numero_documento')) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <hr>

    <!-- Mostrar resultado de la búsqueda -->
    @if(isset($user))
        <!-- Modal para mostrar la información del visitante -->
        <div class="modal fade" id="visitorModal{{ $user->id }}" tabindex="-1" aria-labelledby="visitorModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-center">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="visitorModalLabel{{ $user->id }}">Credencial de {{ $user->nombres }} {{ $user->apellidos }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- Foto del visitante -->
                            @if($user->foto_webcam)
                                <img src="{{ asset('storage/' . $user->foto_webcam) }}" alt="Foto de {{ $user->nombres }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                            @else
                                <div class="text-muted">Sin Foto</div>
                            @endif
                        </div>
                        <p><strong>Cédula:</strong> {{ $user->numero_documento }}</p>
                        <p><strong>Nombre:</strong> {{ $user->nombres }} {{ $user->apellidos }}</p>
                        <p><strong>Área:</strong> {{ $user->area }}</p>
                        <p><strong>RH:</strong> {{ $user->rh }}</p>
                        <p><strong>Género:</strong> {{ $user->genero }}</p>
                        <p><strong>Fecha de Nacimiento:</strong> {{ $user->fecha_nacimiento }}</p>

                        <!-- Botones para registrar entrada o salida -->
                        <div class="mt-3">
                            @if(!$activeLog)
                                <form action="{{ route('visitante.entrada', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Registrar Entrada</button>
                                </form>
                                <button class="btn btn-secondary" disabled>Registrar Salida</button>
                            @else
                                <button class="btn btn-secondary" disabled>Registrar Entrada</button>
                                <form action="{{ route('visitante.salida', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Registrar Salida</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto abrir la modal cuando el visitante es encontrado -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('visitorModal{{ $user->id }}'), {
                    keyboard: false
                });
                myModal.show();
            });
        </script>

    @elseif(request()->has('numero_documento'))
        <!-- Modal si el visitante no está registrado -->
        <div class="modal fade" id="notFoundVisitorModal" tabindex="-1" aria-labelledby="notFoundVisitorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="notFoundVisitorModalLabel">Visitante No Encontrado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>El visitante no está registrado.</p>
                        <a href="{{ route('incomes.create') }}" class="btn btn-warning">Registrar Nuevo Visitante</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto abrir la modal si el visitante no está registrado -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModalNotFound = new bootstrap.Modal(document.getElementById('notFoundVisitorModal'), {
                    keyboard: false
                });
                myModalNotFound.show();
            });
        </script>
    @endif

    <hr>

    <!-- Tabla de visitantes actualmente dentro -->
    <h4 class="mt-4">Visitantes actualmente dentro</h4>
    @if($visitorsInside->count())
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Área</th>
                <th>Hora de Entrada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitorsInside as $log)
            <tr>
                <td>{{ $log->visitor->numero_documento }}</td>
                <td>{{ $log->visitor->nombres }} {{ $log->visitor->apellidos }}</td>
                <td>{{ $log->visitor->area }}</td>
                <td>{{ $log->entry_time }}</td>
                <td>
                    <!-- Botón para registrar salida -->
                    <form action="{{ route('visitante.salida', $log->visitor->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Registrar Salida</button>
                    </form>
                    
                    <!-- Botón para mostrar la credencial -->
                    <button class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#credencialModal{{ $log->visitor->id }}">Mostrar Credencial</button>

                    <!-- Modal para mostrar la credencial del visitante -->
                    <div class="modal fade" id="credencialModal{{ $log->visitor->id }}" tabindex="-1" aria-labelledby="credencialModalLabel{{ $log->visitor->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content text-center">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="credencialModalLabel{{ $log->visitor->id }}">Credencial de {{ $log->visitor->nombres }} {{ $log->visitor->apellidos }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <!-- Foto del visitante -->
                                        @if($log->visitor->foto_webcam)
                                            <img src="{{ asset('storage/' . $log->visitor->foto_webcam) }}" alt="Foto de {{ $log->visitor->nombres }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                                        @else
                                            <div class="text-muted">Sin Foto</div>
                                        @endif
                                    </div>
                                    <p><strong>Cédula:</strong> {{ $log->visitor->numero_documento }}</p>
                                    <p><strong>Nombre:</strong> {{ $log->visitor->nombres }} {{ $log->visitor->apellidos }}</p>
                                    <p><strong>Área:</strong> {{ $log->visitor->area }}</p>
                                    <p><strong>RH:</strong> {{ $log->visitor->rh }}</p>
                                    <p><strong>Género:</strong> {{ $log->visitor->genero }}</p>
                                    <p><strong>Fecha de Nacimiento:</strong> {{ $log->visitor->fecha_nacimiento }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No hay visitantes dentro en este momento.</p>
    @endif
</div>
@endsection
