@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registrar Administrador</h2>

        {{-- Botón en la esquina superior derecha --}}
        <a href="{{ route('admin.search') }}" class="btn btn-info">Ver usuarios</a>
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

    {{-- Formulario --}}
    <form action="{{ route('admin.store') }}" method="POST">
        @csrf

        {{-- Usuario --}}
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de usuario</label>
            <input 
                type="text" 
                class="form-control @error('username') is-invalid @enderror" 
                id="username" 
                name="username" 
                value="{{ old('username') }}" 
                required
            >
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
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

        {{-- Confirmar contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input 
                type="password" 
                class="form-control" 
                id="password_confirmation" 
                name="password_confirmation" 
                required
            >
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
</div>
@endsection
