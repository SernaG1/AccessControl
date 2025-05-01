@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registro de Ingreso de Visitante</h2>

    <form action="{{ route('incomes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Datos personales --}}
        <div class="mb-3">
            <label for="numero_documento" class="form-label">Número de Documento</label>
            <input type="text" name="numero_documento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control">
        </div>

        <div class="mb-3">
            <label for="genero" class="form-label">Género</label>
            <select name="genero" class="form-select">
                <option value="">Selecciona</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="rh" class="form-label">Tipo de Sangre (RH)</label>
            <select name="rh" class="form-select">
                <option value="">Selecciona</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>

        {{-- Datos de visita --}}
        <div class="mb-3">
            <label for="area" class="form-label">Área a Visitar</label>
            <select name="area" class="form-select">
                <option value="">Selecciona</option>
                <option value="Contabilidad">Contabilidad</option>
                <option value="Operaciones">Operaciones</option>
                <option value="Gerencia">Gerencia</option>
                <option value="Auxiliar administrativo">Auxiliar administrativo</option>
                <option value="Gestión humana">Gestión humana</option>
                <option value="HSEQ">HSEQ</option>
                <option value="Almacén">Almacén</option>
                <option value="Selección">Selección</option>
            </select>
        </div>

        {{-- Captura de imagen --}}
        <div class="mb-3">
            <label class="form-label">Capturar Foto (Webcam)</label>
            <div id="my_camera" class="mb-2" style="width:320px; height:240px; border:1px solid #ccc;"></div>
            <button type="button" class="btn btn-info mt-2" onclick="take_snapshot()">Capturar Foto</button>
        </div>

        {{-- Vista previa --}}
        <div class="mb-3">
            <label class="form-label">Vista previa de la foto</label>
            <div id="my_result"></div>
        </div>

        {{-- Campo oculto para guardar la imagen --}}
        <input type="hidden" name="foto_webcam" id="foto_webcam">

        {{-- Botón de envío --}}
        <button type="submit" class="btn btn-primary">Registrar Ingreso</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<script>
    // Configurar la webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            // Mostrar imagen capturada
            document.getElementById('my_result').innerHTML = '<img src="'+data_uri+'" class="img-thumbnail"/>';

            // Guardar base64 en el input oculto
            document.getElementById('foto_webcam').value = data_uri;
        });
    }
</script>
@endsection
