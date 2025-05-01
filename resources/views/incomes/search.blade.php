@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Listado de Visitantes</h2>

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
                    <!-- Botón para abrir modal -->
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#visitorModal{{ $visitor->id }}">
                        Ver Credencial
                    </button>

                    <a href="{{ route('incomes.edit', $visitor->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('incomes.destroy', $visitor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este visitante?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>

            <!-- Modal de credencial para cada visitante -->
            <div class="modal fade" id="visitorModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="visitorModalLabel{{ $visitor->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content text-center">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Credencial de {{ $visitor->nombres }} {{ $visitor->apellidos }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
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
