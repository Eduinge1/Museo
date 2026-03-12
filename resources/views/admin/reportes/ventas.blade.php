@extends('layouts.admin')

@section('title', 'Centro de Reportes — Museo de Arte Contemporáneo')

@section('topbar_title', 'Centro de Reportes')
@section('topbar_subtitle', 'Período: ' . \Carbon\Carbon::parse($fechaInicio)->format('d M Y') . ' — ' . \Carbon\Carbon::parse($fechaFin)->format('d M Y'))

@section('topbar_actions')
<button class="btn-export btn-export-xls" onclick="alert('Exportando a Excel...')"><i class="bi bi-file-earmark-spreadsheet"></i> Excel</button>
<button class="btn-export btn-export-pdf" onclick="alert('Generando PDF...')"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
<button class="btn-export btn-export-print" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
@endsection

@section('extra_css')
<style>
    /* ─── FILTER BAR ─── */
    .filter-card {
      background: #fff; border: 1px solid #eee; border-radius: 16px;
      padding: 1.2rem 1.5rem; margin-bottom: 2rem;
      display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .filter-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #aaa; }
    .filter-ctrl {
      border: 1.5px solid #e0e0e0; border-radius: 10px;
      padding: 0.55rem 0.9rem; font-size: 0.875rem;
      font-family: 'DM Sans', sans-serif; color: var(--dark);
      background: var(--light); outline: none; min-width: 140px;
      transition: border-color 0.2s; cursor: pointer;
    }
    .filter-ctrl:focus { border-color: var(--accent-3); }
    .btn-filter-apply { background: var(--dark); color: #fff; border: none; border-radius: 10px; padding: 0.58rem 1.3rem; font-size: 0.875rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; gap: 0.4rem; }
    .btn-filter-apply:hover { background: var(--accent-4); transform: translateY(-1px); }

    /* ─── KPI GRID ─── */
    .kpi-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 1rem; margin-bottom: 2rem; }
    .kpi-card {
      background: #fff; border-radius: 16px; padding: 1.3rem 1.4rem;
      border: 1px solid #eee; position: relative; overflow: hidden;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .kpi-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .kpi-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; }
    .k1::after { background: var(--accent-1); }
    .k2::after { background: var(--accent-2); }
    .k3::after { background: var(--success); }
    .k4::after { background: var(--accent-4); }
    .k5::after { background: var(--accent-3); }
    .kpi-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; margin-bottom: 0.9rem; }
    .k1 .kpi-icon { background: linear-gradient(135deg,var(--accent-1),#ff8fa3); }
    .k2 .kpi-icon { background: linear-gradient(135deg,var(--accent-2),#fb8500); }
    .k3 .kpi-icon { background: linear-gradient(135deg,var(--success),#118ab2); }
    .k4 .kpi-icon { background: linear-gradient(135deg,var(--accent-4),var(--accent-3)); }
    .k5 .kpi-icon { background: linear-gradient(135deg,var(--accent-3),#48cae4); }
    .kpi-value { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 700; color: var(--dark); line-height: 1; margin-bottom: 0.25rem; }
    .kpi-label { font-size: 0.72rem; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; }

    /* ─── TABLE ─── */
    .panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; margin-bottom: 1.5rem; }
    .panel-head { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
    .panel-head-title { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 700; color: var(--dark); }
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table thead th { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #bbb; padding: 0.65rem 1.2rem; background: #fafafa; border-bottom: 1px solid #f0f0f0; white-space: nowrap; }
    .report-table tbody td { padding: 0.8rem 1.2rem; font-size: 0.84rem; border-bottom: 1px solid #f6f6f6; vertical-align: middle; }
    .price-col { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 0.9rem; }

    .summary-footer {
      background: var(--dark); border-radius: 16px; padding: 1.5rem 2rem;
      display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem;
      margin-bottom: 1.5rem; position: relative; overflow: hidden;
    }
    .sf-item { position: relative; z-index: 1; }
    .sf-label { font-size: 0.65rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 0.4rem; }
    .sf-value { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
      <!-- FILTER BAR -->
      <form action="{{ route('admin.reportes.ventas') }}" method="GET" class="filter-card">
        <div class="filter-group">
          <span class="filter-label">Desde</span>
          <input type="date" name="fecha_inicio" class="filter-ctrl" value="{{ $fechaInicio }}" />
        </div>
        <div class="filter-group">
          <span class="filter-label">Hasta</span>
          <input type="date" name="fecha_fin" class="filter-ctrl" value="{{ $fechaFin }}" />
        </div>
        <button type="submit" class="btn-filter-apply"><i class="bi bi-funnel"></i> Aplicar Filtros</button>
      </form>

      <!-- KPI CARDS -->
      <div class="kpi-grid">
        <div class="kpi-card k1">
          <div class="kpi-icon"><i class="bi bi-bag-check"></i></div>
          <div class="kpi-value">{{ $ventas->count() }}</div>
          <div class="kpi-label">Obras vendidas</div>
        </div>
        <div class="kpi-card k2">
          <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
          <div class="kpi-value">${{ number_format($totalCobrado / 1000, 1) }}k</div>
          <div class="kpi-label">Ingresos totales</div>
        </div>
        <!-- (KPIs estáticos o calculados según disponibilidad de datos) -->
      </div>

      <!-- OBRAS VENDIDAS TABLE -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <div class="panel-head-title">Listado de Obras Vendidas</div>
          </div>
        </div>
        <table class="report-table">
          <thead>
            <tr>
              <th>ID Venta</th>
              <th>Obra</th>
              <th>Artista</th>
              <th>Comprador</th>
              <th>Total Cobrado</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ventas as $venta)
            <tr>
              <td class="fw-bold">#{{ $venta->id }}</td>
              <td>{{ $venta->obra->titulo }}</td>
              <td>{{ $venta->obra->artista->nombre ?? 'N/A' }}</td>
              <td>{{ $venta->comprador->user->name ?? 'N/A' }}</td>
              <td class="price-col text-primary">${{ number_format($venta->factura->precio_venta ?? 0, 2) }}</td>
              <td>{{ \Carbon\Carbon::parse($venta->fecha_concretacion)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">No se encontraron ventas en este período.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- SUMMARY FOOTER -->
      <div class="summary-footer">
        <div class="sf-item">
          <div class="sf-label">Total ingresos</div>
          <div class="sf-value">${{ number_format($totalCobrado, 2) }}</div>
        </div>
        <div class="sf-item">
          <div class="sf-label">Obras</div>
          <div class="sf-value">{{ $ventas->count() }}</div>
        </div>
      </div>
</div>
@endsection
