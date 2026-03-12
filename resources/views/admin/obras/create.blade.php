@extends('layouts.admin')

@section('title', 'Nueva Obra — Admin')

@section('topbar_title', 'Registrar Nueva Obra')
@section('topbar_subtitle', 'Completa los datos de la obra de arte')

@section('content')
<div class="panel p-4">
    <form action="{{ route('admin.obras.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Título de la Obra</label>
                <input type="text" name="titulo" class="form-control" required placeholder="Ej: Sinfonía en Azul">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Artista</label>
                <select name="id_artista" class="form-select" required>
                    <option value="">Seleccione un artista</option>
                    @foreach($artistas as $artista)
                        <option value="{{ $artista->id }}">{{ $artista->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Género / Categoría</label>
                <select name="id_genero" id="id_genero" class="form-select" required>
                    <option value="">Seleccione un género</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}" data-nombre="{{ $genero->nombre }}">{{ $genero->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="genero_nombre" id="genero_nombre">

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Precio de Venta ($)</label>
                <input type="number" name="precio_venta" class="form-control" required min="0" step="0.01">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Creación</label>
                <input type="date" name="fecha_creacion" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Imagen de la Obra</label>
                <input type="file" name="imagen" id="imagen" class="form-control" required accept="image/*">
            </div>
        </div>

        <!-- Campos Dinámicos por Género -->
        <div id="dynamic-fields" class="mt-4 p-3 bg-light rounded border" style="display:none;">
            <h5 class="mb-3 border-bottom pb-2">Detalles Específicos del Género</h5>
            
            <!-- Fotografía -->
            <div class="row genre-section" id="section-Fotografía" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Resolución</label>
                    <input type="text" name="resolucion" class="form-control" placeholder="Ej: 300dpi, 4K">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Impresión</label>
                    <input type="text" name="tipo_impresion" class="form-control" placeholder="Ej: Papel Mate, Canvas">
                </div>
            </div>

            <!-- Pintura -->
            <div class="row genre-section" id="section-Pintura" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Técnica</label>
                    <input type="text" name="nombre_tecnica" class="form-control" placeholder="Ej: Óleo, Acuarela">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Soporte</label>
                    <input type="text" name="nombre_soporte" class="form-control" placeholder="Ej: Lienzo, Madera">
                </div>
            </div>

            <!-- Escultura -->
            <div class="row genre-section" id="section-Escultura" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Material</label>
                    <input type="text" name="nombre_material" class="form-control" placeholder="Ej: Mármol, Bronce">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" step="0.1">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Alto (cm)</label>
                    <input type="number" name="dimensiones_alto" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Largo (cm)</label>
                    <input type="number" name="dimensiones_largo" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ancho (cm)</label>
                    <input type="number" name="dimensiones_ancho" class="form-control">
                </div>
            </div>

            <!-- Cerámica -->
            <div class="row genre-section" id="section-Cerámica" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Arcilla</label>
                    <input type="text" name="tipo_arcilla" class="form-control" placeholder="Ej: Terracota, Porcelana">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Técnica de Cocción</label>
                    <input type="text" name="tecnica_coccion" class="form-control" placeholder="Ej: Horno de Leña, Raku">
                </div>
            </div>

            <!-- Orfebrería -->
            <div class="row genre-section" id="section-Orfebrería" style="display:none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metal Principal</label>
                    <input type="text" name="metal_principal" class="form-control" placeholder="Ej: Oro 18k, Plata 950">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (gramos)</label>
                    <input type="number" name="peso_gramos" class="form-control" step="0.01">
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.obras.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Guardar Obra</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generoSelect = document.getElementById('id_genero');
        const dynamicFields = document.getElementById('dynamic-fields');
        const sections = document.querySelectorAll('.genre-section');
        const generoNombreInput = document.getElementById('genero_nombre');

        generoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
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
        });

        // Validación de imagen (existente)
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