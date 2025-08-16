<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts y Estilos Personalizados --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .container {
            max-width: 400px;
            padding: 40px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 100px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            border-top: 1px solid #e1e1e1;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    {{-- Contenedor del formulario de login --}}
    <div class="container">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>

        {{-- Formulario de autenticación --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Campo para el nombre de usuario --}}
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                       id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo para la contraseña --}}
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botón para enviar el formulario --}}
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>

        {{-- Mensaje de error si las credenciales son incorrectas --}}
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer>
        &copy; {{ date('Y') }} Empresa Napoles. Todos los derechos reservados.
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
