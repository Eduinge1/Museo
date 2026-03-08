@extends('layouts.admin')

@section('title', 'Gestión de Artistas — Museo de Arte Contemporáneo')

@section('topbar_title', 'Gestión de Artistas')
@section('topbar_subtitle', $artistas->total() . ' artistas registrados')

@section('topbar_actions')
<a href="{{ route('admin.artistas.create') }}" class="btn-add text-decoration-none"><i class="bi bi-person-plus"></i> Nuevo Artista</a>
<a href="{{ route('admin.obras.index') }}" class="btn-add-secondary text-decoration-none"><i class="bi bi-palette"></i> Ver Obras</a>
@endsection

@section('extra_css')
<style>
    /* TABS */
    .tab-row { display: flex; gap: 0.4rem; margin-bottom: 1.5rem; }
    .tab-btn {
      background: #fff; border: 1.5px solid #eee; border-radius: 10px;
      padding: 0.5rem 1.2rem; font-size: 0.85rem; font-weight: 500;
      color: #888; cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; gap: 0.4rem;
      font-family: 'DM Sans', sans-serif;
    }
    .tab-btn.active { background: var(--dark); border-color: var(--dark); color: #fff; }
    .tab-btn:not(.active):hover { border-color: #ccc; color: var(--dark); }
    .tab-count { background: rgba(255,255,255,0.2); border-radius: 50px; padding: 0.05rem 0.45rem; font-size: 0.7rem; }

    /* TABLE */
    .table-panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th {
      font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: 1px; color: #bbb; padding: 0.8rem 1.2rem;
      background: #fafafa; border-bottom: 1px solid #f0f0f0; white-space: nowrap;
    }
    .table-custom tbody tr { border-bottom: 1px solid #f6f6f6; }
    .table-custom td { padding: 0.9rem 1.2rem; font-size: 0.855rem; vertical-align: middle; }

    .artista-cell { display: flex; align-items: center; gap: 0.8rem; }
    .artista-thumb { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; }
    .artista-name { font-weight: 600; color: var(--dark); }
    .artista-meta { font-size: 0.72rem; color: #aaa; }

    .actions-cell { display: flex; gap: 0.4rem; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fff; color: #888; }
    
    .genre-tag {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background: #f0f0f0;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-right: 0.2rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
      <!-- TABS -->
      <div class="tab-row">
        <a href="{{ route('admin.obras.index') }}" class="tab-btn text-decoration-none"><i class="bi bi-palette"></i> Obras</a>
        <button class="tab-btn active"><i class="bi bi-people"></i> Artistas <span class="tab-count">{{ $artistas->total() }}</span></button>
      </div>

      <!-- ARTISTAS TABLE -->
      <div class="table-panel">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Artista</th>
              <th>Nacionalidad</th>
              <th>Nacimiento / Defunción</th>
              <th>Géneros</th>
              <th>Obras</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($artistas as $artista)
            <tr>
              <td>
                <div class="artista-cell">
                  <img src="{{ $artista->image_url ?? 'https://via.placeholder.com/44' }}" class="artista-thumb" alt="">
                  <div><div class="artista-name">{{ $artista->nombre }}</div><div class="artista-meta">ID: {{ $artista->id }}</div></div>
                </div>
              </td>
              <td>{{ $artista->nacionalidad }}</td>
              <td>
                {{ $artista->fecha_nacimiento ? $artista->fecha_nacimiento->format('Y') : 'N/A' }} 
                - 
                {{ $artista->fecha_defuncion ? $artista->fecha_defuncion->format('Y') : 'Actualidad' }}
              </td>
              <td>
                @foreach($artista->generos as $genero)
                    <span class="genre-tag">{{ $genero->nombre }}</span>
                @endforeach
              </td>
              <td>{{ $artista->obras_count ?? $artista->obras()->count() }}</td>
              <td>
                <div class="actions-cell">
                  <a href="{{ route('admin.artistas.edit', $artista->id) }}" class="btn-icon"><i class="bi bi-pencil"></i></a>
                  <form action="{{ route('admin.artistas.destroy', $artista->id) }}" method="POST" onsubmit="return confirm('¿Eliminar artista?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn-icon"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="p-3">
            {{ $artistas->links() }}
        </div>
      </div>
</div>
@endsection
