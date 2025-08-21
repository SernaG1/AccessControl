@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Administradores</h2>
        <a href="{{ route('admin.create') }}" class="btn btn-success">➕ Nuevo Administrador</a>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabla dinámica --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($web_users as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->username }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No hay administradores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $web_users->links() }}
    </div>
</div>
@endsection