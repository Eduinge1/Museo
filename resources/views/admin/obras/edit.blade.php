@extends('layouts.admin')

@section('title', 'Editar Obra — Admin')

@section('topbar_title', 'Editar Obra')
@section('topbar_subtitle', 'Modifica los datos de: ' . $obra->titulo)

@section('content')
<div class="panel p-4 bg-white shadow-sm rounded">
    <form action="{{ route('admin.obras.update', $obra->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Título de la Obra</label>
                <input type="text" name="titulo" class="form-control" required value="{{ old('titulo', $obra->titulo) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Artista</label>
                <select name="id_artista" class="form-select" required>
                    @foreach($artistas as $artista)
                        <option value="{{ $artista->id }}" {{ $obra->id_artista == $artista->id ? 'selected' : '' }}>
                            {{ $artista->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Género / Categoría</label>
                <select name="id_genero" id="id_genero" class="form-select" required>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}" data-nombre="{{ $genero->nombre }}" {{ $obra->id_genero == $genero->id ? 'selected' : '' }}>
                            {{ $genero->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="genero_nombre" id="genero_nombre" value="{{ $obra->genero->nombre }}">

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Precio de Venta ($)</label>
                <input type="number" name="precio_venta" class="form-control" required min="0" step="0.01" value="{{ old('precio_venta', $obra->precio_venta) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control" required value="{{ $obra->fecha_creacion instanceof \DateTime ? $obra->fecha_creacion->format('Y-m-d') : substr($obra->fecha_creacion, 0, 10) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Estado de Disponibilidad</label>
                <select name="estado" class="form-select border-primary">
                    <option value="Disponible" {{ $obra->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="Reservada" {{ $obra->estado == 'Reservada' ? 'selected' : '' }}>Reservada</option>
                    <option value="Vendida" {{ $obra->estado == 'Vendida' ? 'selected' : '' }}>Vendida</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Imagen (URL o Archivo)</label>
                <input type="text" name="image_url" class="form-control mb-2" placeholder="URL de internet..." value="{{ $obra->image_url }}">
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6 mb-3 text-center">
                <label class="form-label fw-bold d-block">Vista Previa Actual</label>
                @if($obra->image_url)
                    <img src="{{ Str::startsWith($obra->image_url, 'http') ? $obra->image_url : asset($obra->image_url) }}" 
                         class="img-thumbnail" style="max-height: 120px;">
                @endif
            </div>
        </div>

        <div id="dynamic-fields" class="mt-4 p-3 bg-light rounded border">
            <h5 class="mb-3 border-bottom pb-2">Detalles Específicos</h5>
            
            <div class="row genre-section" id="section-Pintura" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Técnica</label>
                    <input type="text" name="nombre_tecnica" class="form-control" value="{{ $detalle->nombre_tecnica ?? '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Soporte</label>
                    <input type="text" name="nombre_soporte" class="form-control" value="{{ $detalle->nombre_soporte ?? '' }}">
                </div>
            </div>

            </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.obras.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-dark px-4">Actualizar Obra</button>
        </div>
    </form>
</div>

<script>
    // Tu script anterior de JS funciona perfecto aquí
    document.addEventListener('DOMContentLoaded', function() {
        const generoSelect = document.getElementById('id_genero');
        const sections = document.querySelectorAll('.genre-section');
        const dynamicFields = document.getElementById('dynamic-fields');

        function updateGenreFields() {
            const selectedOption = generoSelect.options[generoSelect.selectedIndex];
            const generoNombre = selectedOption.getAttribute('data-nombre');
            
            sections.forEach(s => s.style.display = 'none');
            const targetSection = document.getElementById('section-' + generoNombre);
            if (targetSection) {
                dynamicFields.style.display = 'block';
                targetSection.style.display = 'flex';
            }
        }
        generoSelect.addEventListener('change', updateGenreFields);
        updateGenreFields();
    });
</script>
@endsection