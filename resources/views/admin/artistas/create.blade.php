@extends('layouts.admin')

@section('title', 'Nuevo Artista — Admin')

@section('topbar_title', 'Registrar Nuevo Artista')
@section('topbar_subtitle', 'Completa la información del artista')

@section('content')
<div class="panel p-4" style="background: #fff; border-radius: 16px; border: 1px solid #eee;">
    <form action="{{ route('admin.artistas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nombre del Artista</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required placeholder="Ej: Vincent van Gogh">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nacionalidad</label>
                <input type="text" name="nacionalidad" class="form-control @error('nacionalidad') is-invalid @enderror" value="{{ old('nacionalidad') }}" required placeholder="Ej: Neerlandés">
                @error('nacionalidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Defunción</label>
                <input type="date" name="fecha_defuncion" class="form-control @error('fecha_defuncion') is-invalid @enderror" value="{{ old('fecha_defuncion') }}">
                <div class="form-text">Dejar en blanco si el artista sigue vivo.</div>
                @error('fecha_defuncion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Géneros / Especialidades</label>
                <select name="id_genero[]" class="form-select @error('id_genero') is-invalid @enderror" multiple required style="height: 120px;">
                    @foreach($generos as $genero)
                        <option value="{{ $genero->id }}" {{ (is_array(old('id_genero')) && in_array($genero->id, old('id_genero'))) ? 'selected' : '' }}>
                            {{ $genero->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar varios.</div>
                @error('id_genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Imagen de Retrato</label>
                <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" required accept="image/*">
                @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.artistas.index') }}" class="btn btn-light border">Cancelar</a>
            <button type="submit" class="btn btn-dark">Guardar Artista</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imagenInput = document.getElementById('imagen');
        
        if (imagenInput) {
            imagenInput.addEventListener('change', function() {
                const file = this.files[0];
                
                if (file) {
                    // Validar que el archivo sea de tipo imagen
                    if (!file.type.startsWith('image/')) {
                        alert('Por favor, selecciona un archivo de tipo imagen válido.');
                        this.value = ''; // Limpiar el input
                        return;
                    }

                    // Validar tamaño máximo (5 MB)
                    const maxSizeInBytes = 5 * 1024 * 1024;
                    if (file.size > maxSizeInBytes) {
                        alert('El tamaño de la imagen no puede superar los 5 MB.');
                        this.value = ''; // Limpiar el input
                        return;
                    }
                }
            });
        }
    });
</script>
@endsection