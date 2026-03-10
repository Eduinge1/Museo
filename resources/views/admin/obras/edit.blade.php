@extends('layouts.admin')

@section('title', 'Editar Obra — Admin')

@section('topbar_title', 'Editar Obra')
@section('topbar_subtitle', 'Modifica los datos de: ' . $obra->titulo)

@section('content')
<div class="panel p-4">
    <form action="{{ route('admin.obras.update', $obra->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Título de la Obra</label>
                <input type="text" name="titulo" class="form-control" required value="{{ $obra->titulo }}">
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
                <input type="number" name="precio_venta" class="form-control" required min="0" step="0.01" value="{{ $obra->precio_venta }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control" required value="{{ $obra->fecha_creacion instanceof \DateTime ? $obra->fecha_creacion->format('Y-m-d') : substr($obra->fecha_creacion, 0, 10) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Estado</label>
                <select name="estado" class="form-select">
                    <option value="Disponible" {{ $obra->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="Reservada" {{ $obra->estado == 'Reservada' ? 'selected' : '' }}>Reservada</option>
                    <option value="Vendida" {{ $obra->estado == 'Vendida' ? 'selected' : '' }}>Vendida</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Cambiar Imagen de la Obra</label>
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                <small class="text-muted">Deja este campo vacío si no deseas cambiar la imagen actual.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold d-block">Imagen Actual</label>
                @if($obra->image_url)
                    <img src="{{ asset($obra->image_url) }}" alt="{{ $obra->titulo }}" class="img-thumbnail" style="max-height: 100px;">
                @else
                    <span class="text-muted">No hay imagen registrada</span>
                @endif
            </div>
        </div>

        <!-- Campos Dinámicos por Género -->
        <div id="dynamic-fields" class="mt-4 p-3 bg-light rounded border">
            <h5 class="mb-3 border-bottom pb-2">Detalles Específicos del Género</h5>
            
            <!-- Fotografía -->
            <div class="row genre-section" id="section-Fotografía" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Resolución</label>
                    <input type="text" name="resolucion" class="form-control" value="{{ $obra->genero->nombre == 'Fotografía' ? $detalle->resolucion ?? '' : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Impresión</label>
                    <input type="text" name="tipo_impresion" class="form-control" value="{{ $obra->genero->nombre == 'Fotografía' ? $detalle->tipo_impresion ?? '' : '' }}">
                </div>
            </div>

            <!-- Pintura -->
            <div class="row genre-section" id="section-Pintura" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Técnica</label>
                    <input type="text" name="nombre_tecnica" class="form-control" value="{{ $obra->genero->nombre == 'Pintura' ? $detalle->nombre_tecnica ?? '' : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Soporte</label>
                    <input type="text" name="nombre_soporte" class="form-control" value="{{ $obra->genero->nombre == 'Pintura' ? $detalle->nombre_soporte ?? '' : '' }}">
                </div>
            </div>

            <!-- Escultura -->
            <div class="row genre-section" id="section-Escultura" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Material</label>
                    <input type="text" name="nombre_material" class="form-control" value="{{ $obra->genero->nombre == 'Escultura' ? $detalle->nombre_material ?? '' : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" step="0.1" value="{{ $obra->genero->nombre == 'Escultura' ? $detalle->peso ?? '' : '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Alto (cm)</label>
                    <input type="number" name="dimensiones_alto" class="form-control" value="{{ $obra->genero->nombre == 'Escultura' ? $detalle->dimensiones_alto ?? '' : '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Largo (cm)</label>
                    <input type="number" name="dimensiones_largo" class="form-control" value="{{ $obra->genero->nombre == 'Escultura' ? $detalle->dimensiones_largo ?? '' : '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ancho (cm)</label>
                    <input type="number" name="dimensiones_ancho" class="form-control" value="{{ $obra->genero->nombre == 'Escultura' ? $detalle->dimensiones_ancho ?? '' : '' }}">
                </div>
            </div>

            <!-- Cerámica -->
            <div class="row genre-section" id="section-Cerámica" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Arcilla</label>
                    <input type="text" name="tipo_arcilla" class="form-control" value="{{ $obra->genero->nombre == 'Cerámica' ? $detalle->tipo_arcilla ?? '' : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Técnica de Cocción</label>
                    <input type="text" name="tecnica_coccion" class="form-control" value="{{ $obra->genero->nombre == 'Cerámica' ? $detalle->tecnica_coccion ?? '' : '' }}">
                </div>
            </div>

            <!-- Orfebrería -->
            <div class="row genre-section" id="section-Orfebrería" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metal Principal</label>
                    <input type="text" name="metal_principal" class="form-control" value="{{ $obra->genero->nombre == 'Orfebrería' ? $detalle->metal_principal ?? '' : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (gramos)</label>
                    <input type="number" name="peso_gramos" class="form-control" step="0.01" value="{{ $obra->genero->nombre == 'Orfebrería' ? $detalle->peso_gramos ?? '' : '' }}">
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.obras.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Actualizar Obra</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generoSelect = document.getElementById('id_genero');
        const dynamicFields = document.getElementById('dynamic-fields');
        const sections = document.querySelectorAll('.genre-section');
        const generoNombreInput = document.getElementById('genero_nombre');

        function updateGenreFields() {
            const selectedOption = generoSelect.options[generoSelect.selectedIndex];
            const generoNombre = selectedOption.getAttribute('data-nombre');
            
            generoNombreInput.value = generoNombre;

            // Ocultar todas las secciones
            sections.forEach(s => s.style.display = 'none');
            dynamicFields.style.display = 'none';

            if (generoNombre) {
                const targetSection = document.getElementById('section-' + generoNombre);
                if (targetSection) {
                    dynamicFields.style.display = 'block';
                    targetSection.style.display = 'flex';
                }
            }
        }

        generoSelect.addEventListener('change', updateGenreFields);
        
        // Ejecutar al cargar para mostrar el género actual
        updateGenreFields();

        // Validación de imagen
        const imagenInput = document.getElementById('imagen');
        if (imagenInput) {
            imagenInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Por favor, selecciona un archivo de tipo imagen válido.');
                        this.value = '';
                        return;
                    }
                    const maxSizeInBytes = 5 * 1024 * 1024;
                    if (file.size > maxSizeInBytes) {
                        alert('El tamaño de la imagen no puede superar los 5 MB.');
                        this.value = '';
                        return;
                    }
                }
            });
        }
    });
</script>
@endsection