@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Listado de Visitantes</h2>

    <form method="GET" action="{{ route('incomes.searchUser') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    @if($visitors->count())
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
            @foreach($visitors as $visitor)
            <tr>
                <td>{{ $visitor->numero_documento }}</td>
                <td>{{ $visitor->nombres }} {{ $visitor->apellidos }}</td>
                <td>{{ $visitor->area }}</td>
                <td>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#visitorModal{{ $visitor->id }}">
                        Ver Información
                    </button>

                    <a href="{{ route('incomes.edit', $visitor->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('incomes.destroy', $visitor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este visitante?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>

            <!-- Modal de información para cada visitante -->
            <div class="modal fade" id="visitorModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="visitorModalLabel{{ $visitor->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content text-center">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="visitorModalLabel{{ $visitor->id }}">Información de {{ $visitor->nombres }} {{ $visitor->apellidos }}</h5>
                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            @if($visitor->foto_webcam)
                                <img src="{{ asset('storage/' . $visitor->foto_webcam) }}" class="img-fluid rounded-circle mb-3" alt="Foto" style="max-width: 150px;">
                            @else
                                <div class="text-muted">Sin foto disponible</div>
                            @endif
                            <p><strong>Cédula:</strong> {{ $visitor->numero_documento }}</p>
                            <p><strong>Nombre:</strong> {{ $visitor->nombres }} {{ $visitor->apellidos }}</p>
                            <p><strong>Área:</strong> {{ $visitor->area }}</p>
                            <p><strong>RH:</strong> {{ $visitor->rh }}</p>
                            <p><strong>Género:</strong> {{ $visitor->genero }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $visitor->fecha_nacimiento }}</p>
                            <p><strong>Teléfono:</strong> {{ $visitor->telefono }}</p>
                            <p><strong>Contacto Emergencia:</strong> {{ $visitor->nombre_contacto_emergencia }}</p>
                            <p><strong>Teléfono Contacto Emergencia:</strong> {{ $visitor->telefono_contacto_emergencia }}</p>
                            <p><strong>Dirección:</strong> {{ $visitor->direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $visitors->links() }}
    </div>
    @else
        <p class="text-center">No hay visitantes registrados aún.</p>
    @endif
</div>
@endsection
