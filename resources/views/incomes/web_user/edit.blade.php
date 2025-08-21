@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Administrador</h2>

        {{-- Botón para volver a la lista --}}
        <a href="{{ route('admin.search') }}" class="btn btn-info">Volver a usuarios</a>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de edición --}}
    <form action="{{ route('admin.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Usuario (solo lectura, no se cambia) --}}
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de usuario</label>
            <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                value="{{ $admin->username }}" 
                readonly
            >
        </div>

        {{-- Contraseña actual --}}
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña actual</label>
            <input 
                type="password" 
                class="form-control @error('current_password') is-invalid @enderror" 
                id="current_password" 
                name="current_password" 
                required
            >
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nueva contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirmar nueva contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
            <input 
                type="password" 
                class="form-control" 
                id="password_confirmation" 
                name="password_confirmation" 
                required
            >
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
    </form>
</div>
@endsection
