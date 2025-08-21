<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Ingreso de Visitantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts y Estilos Personalizados --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

        {{-- Vite (JS y CSS de tu proyecto) --}}
    @vite(['resources/js/app.js'])

    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 45px;
        }

        .container {
            max-width: 960px;
        }

        /* Minimalistic content section styling */
        .content-section {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: box-shadow 0.3s ease;
        }

        .content-section:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Navbar link hover effect */
        .navbar-nav .nav-link {
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            box-shadow: inset 0 -3px 0 0 #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-radius: 4px;
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

    @auth('admin')
        {{-- Navbar corporativa --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
            <div class="container">
                <a class="navbar-brand">
                    <img src="{{ asset('logo_napoles.jpg') }}" alt="Logo Empresa">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employee.index') ? 'active' : '' }}" href="{{ route('employee.index') }}">Ingreso empleados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('visitor.index') ? 'active' : '' }}" href="{{ route('visitor.index') }}">Ingreso visitantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Generar Reporte</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.create') ? 'active' : '' }}" href="{{ route('admin.create') }}">Crear Usuario web</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Contenido principal --}}
        <div class="container bg-white p-4 rounded shadow-sm mb-4">
            {{-- Wrap your main content sections in divs with class "content-section" for minimalistic styling --}}
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer>
            &copy; {{ date('Y') }} Empresa Napoles. Todos los derechos reservados.
        </footer>
    @endauth

    @guest('admin')
            <div class="container text-center mt-5">
                <h2>Debe iniciar sesión para acceder al sistema</h2>
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">Ir al Login</a>
            </div>

    @endguest

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
