@extends('layouts.admin')

@section('title', 'Gestión de Obras — Museo de Arte Contemporáneo')

@section('topbar_title', 'Gestión de Obras y Artistas')
@section('topbar_subtitle', $obras->total() . ' obras registradas')

@section('topbar_actions')
<a href="{{ route('admin.artistas.create') }}" class="btn-add-secondary text-decoration-none"><i class="bi bi-person-plus"></i> Nuevo Artista</a>
<a href="{{ route('admin.obras.create') }}" class="btn-add text-decoration-none"><i class="bi bi-plus-lg"></i> Nueva Obra</a>
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

    /* FILTER BAR */
    .filter-bar {
      background: #fff; border: 1px solid #eee; border-radius: 14px;
      padding: 0.85rem 1.2rem; display: flex; align-items: center;
      gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;
    }
    .filter-search-wrap { flex: 1; min-width: 200px; display: flex; align-items: center; gap: 0.5rem; }
    .filter-input { flex: 1; border: none; outline: none; font-size: 0.875rem; background: transparent; }
    .filter-divider { width: 1px; height: 20px; background: #eee; }
    .filter-select { border: none; outline: none; font-size: 0.83rem; color: #777; background: transparent; cursor: pointer; }

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

    .obra-cell { display: flex; align-items: center; gap: 0.8rem; }
    .obra-thumb { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; }
    .obra-name { font-weight: 600; color: var(--dark); }
    .obra-meta { font-size: 0.72rem; color: #aaa; }
    .price-cell { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 0.95rem; }

    .status-pill { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.7rem; font-weight: 600; border-radius: 50px; padding: 0.25rem 0.7rem; }
    .sp-disponible { background: rgba(6,214,160,0.1); color: #019975; }
    .sp-reservada  { background: rgba(255,190,11,0.12); color: #b8860b; }
    .sp-vendida    { background: rgba(58,134,255,0.1); color: var(--accent-3); }

    .actions-cell { display: flex; gap: 0.4rem; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fff; color: #888; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
      <!-- TABS -->
      <div class="tab-row">
        <button class="tab-btn active"><i class="bi bi-palette"></i> Obras <span class="tab-count">{{ $obras->total() }}</span></button>
        <a href="{{ route('admin.artistas.index') }}" class="tab-btn text-decoration-none"><i class="bi bi-people"></i> Artistas</a>
      </div>

      <!-- FILTER BAR -->
      <div class="filter-bar">
        <div class="filter-search-wrap">
          <i class="bi bi-search"></i>
          <input type="text" class="filter-input" placeholder="Buscar por título o artista..." />
        </div>
        <div class="filter-divider"></div>
        <select class="filter-select">
          <option value="">Todos los géneros</option>
        </select>
        <button class="btn-filter-clear">Limpiar</button>
      </div>

      <!-- OBRAS TABLE -->
      <div class="table-panel">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Obra</th>
              <th>Género</th>
              <th>Precio</th>
              <th>Estado</th>
              <th>Artista</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($obras as $obra)
            <tr>
              <td>
                <div class="obra-cell">
                  <img src="{{ $obra->image_url ?? 'https://via.placeholder.com/44' }}" class="obra-thumb" alt="">
                  <div><div class="obra-name">{{ $obra->titulo }}</div><div class="obra-meta">ID: {{ $obra->id }}</div></div>
                </div>
              </td>
              <td>{{ $obra->genero->nombre ?? 'N/A' }}</td>
              <td class="price-cell">${{ number_format($obra->precio_venta, 0) }}</td>
              <td>
                @php
                    $statusClass = match(strtolower($obra->estado)) {
                        'disponible' => 'sp-disponible',
                        'reservada' => 'sp-reservada',
                        'vendido', 'vendida' => 'sp-vendida',
                        default => ''
                    };
                @endphp
                <span class="status-pill {{ $statusClass }}">{{ ucfirst($obra->estado) }}</span>
              </td>
              <td>{{ $obra->artista->nombre ?? 'N/A' }}</td>
              <td>
                <div class="actions-cell">
                  <a href="{{ route('admin.obras.show', $obra->id) }}" class="btn-icon"><i class="bi bi-eye"></i></a>
                  <a href="{{ route('admin.obras.edit', $obra->id) }}" class="btn-icon"><i class="bi bi-pencil"></i></a>
                  <form action="{{ route('admin.obras.destroy', $obra->id) }}" method="POST" onsubmit="return confirm('¿Eliminar obra?')">
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
            {{ $obras->links() }}
        </div>
      </div>
</div>
@endsection
