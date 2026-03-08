<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar Nueva Obra de Arte
        </h2>
    </x-slot>

    <div class="container">
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('artworks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section">
                <label>Nombre de la Obra:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

                <label>Artista:</label>
                <select name="artist_id" class="form-control" required>
                    <option value="">Seleccione un artista...</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>

                <label>Género:</label>
                <select name="genre_id" id="genre_selector" class="form-control" required onchange="toggleFields()">
                    <option value="">-- Seleccione un Género --</option>
                    <option value="pintura"    {{ old('genre_id') == 'pintura'    ? 'selected' : '' }}>Pintura</option>
                    <option value="escultura"  {{ old('genre_id') == 'escultura'  ? 'selected' : '' }}>Escultura</option>
                    <option value="fotografia" {{ old('genre_id') == 'fotografia' ? 'selected' : '' }}>Fotografía</option>
                    <option value="ceramica"   {{ old('genre_id') == 'ceramica'   ? 'selected' : '' }}>Cerámica</option>
                    <option value="orfebreria" {{ old('genre_id') == 'orfebreria' ? 'selected' : '' }}>Orfebrería</option>
                </select>
            </div>

            <div id="fields_escultura" style="display:none;" class="extra-fields">
                <h4>Detalles de Escultura</h4>
                <label>Material:</label> <input type="text" name="material" class="form-control" value="{{ old('material') }}">
                <label>Peso (kg):</label> <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight') }}">
                <label>Dimensiones:</label> <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}">
            </div>

            <div id="fields_pintura" style="display:none;" class="extra-fields">
                <h4>Detalles de Pintura</h4>
                <label>Técnica:</label> <input type="text" name="technique" class="form-control" value="{{ old('technique') }}">
                <label>Soporte:</label> <input type="text" name="support" class="form-control" value="{{ old('support') }}">
            </div>

            <div id="fields_fotografia" style="display:none;" class="extra-fields">
                <h4>Detalles de Fotografía</h4>
                <label>Nro. de Edición:</label> <input type="text" name="edition" class="form-control" value="{{ old('edition') }}">
                <label>Tipo de Impresión:</label> <input type="text" name="print_type" class="form-control" value="{{ old('print_type') }}">
            </div>

            <div id="fields_ceramica" style="display:none;" class="extra-fields">
                <h4>Detalles de Cerámica</h4>
                <label>Tipo de Arcilla:</label> <input type="text" name="clay_type" class="form-control" value="{{ old('clay_type') }}">
                <label>Temperatura de Cocción:</label> <input type="text" name="temperature" class="form-control" value="{{ old('temperature') }}">
            </div>

            <div id="fields_orfebreria" style="display:none;" class="extra-fields">
                <h4>Detalles de Orfebrería</h4>
                <label>Material Precioso:</label> <input type="text" name="precious_material" class="form-control" value="{{ old('precious_material') }}">
                <label>Pureza (Quilates):</label> <input type="number" name="purity" class="form-control" value="{{ old('purity') }}">
            </div>

            <div class="section">
                <label>Precio Base (USD):</label>
                <input type="number" name="base_price" step="0.01" class="form-control" value="{{ old('base_price') }}" required>

                <label>Ganancia Museo (5-10%):</label>
                <input type="number" name="commission_percentage" min="5" max="10" class="form-control" value="{{ old('commission_percentage') }}" required>

                <label>Foto de la Obra:</label>
                <input type="file" name="photo" class="form-control" required>
            </div>

            <button type="submit" class="btn-success">Guardar Obra</button>
        </form>
    </div>

    <script>
    function toggleFields() {
        const genre = document.getElementById('genre_selector').value;
        document.querySelectorAll('.extra-fields').forEach(s => s.style.display = 'none');
        if (genre) {
            const target = document.getElementById('fields_' + genre);
            if (target) target.style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleFields);
    </script>

    <style>
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-control { width: 100%; padding: 12px; margin-top: 8px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .extra-fields { background-color: #fcfcfc; border-left: 4px solid #333; padding: 20px; margin-bottom: 25px; }
        .btn-success { width: 100%; padding: 15px; background-color: #000; color: #fff; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; text-transform: uppercase; }
        .btn-success:hover { background-color: #444; }
        .alert-danger { background-color: #fff0f0; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        label { font-weight: 600; font-size: 0.9rem; color: #555; }
    </style>
</x-app-layout>