@extends('layouts.admin')

@section('title', 'Reporte Financiero — Museo de Arte Contemporáneo')

@section('topbar_title', 'Reporte Financiero')
@section('topbar_subtitle', 'Resumen de ingresos y facturación del período')

@section('topbar_actions')
<button class="btn-export btn-export-xls" onclick="alert('Exportando a Excel...')"><i class="bi bi-file-earmark-spreadsheet"></i> Excel</button>
<button class="btn-export btn-export-pdf" onclick="alert('Generando PDF...')"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
<button class="btn-export btn-export-print" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
@endsection

@section('extra_css')
<style>
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
    .btn-filter-apply { background: var(--dark); color: #fff; border: none; border-radius: 10px; padding: 0.58rem 1.3rem; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.4rem; }

    .kpi-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 2rem; }
    .kpi-card {
      background: #fff; border-radius: 16px; padding: 1.5rem;
      border: 1px solid #eee; position: relative; overflow: hidden;
    }
    .kpi-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 4px; }
    .k1::after { background: var(--success); }
    .k2::after { background: var(--accent-4); }
    .k3::after { background: var(--accent-2); }
    .kpi-value { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--dark); line-height: 1; margin-bottom: 0.5rem; }
    .kpi-label { font-size: 0.75rem; color: #999; text-transform: uppercase; letter-spacing: 1px; }

    .panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .panel-head { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .panel-head-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); }
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table thead th { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #bbb; padding: 0.8rem 1.2rem; background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    .report-table tbody td { padding: 1rem 1.2rem; font-size: 0.875rem; border-bottom: 1px solid #f6f6f6; }
    .price-col { font-family: 'Playfair Display', serif; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- FILTER BAR -->
    <form action="{{ route('admin.reportes.financiero') }}" method="GET" class="filter-card">
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

    <!-- KPI GRID -->
    <div class="kpi-grid">
        <div class="kpi-card k1">
            <div class="kpi-value">${{ number_format($totalRecaudado, 2) }}</div>
            <div class="kpi-label">Total Recaudado (Ventas + IVA)</div>
        </div>
        <div class="kpi-card k2">
            <div class="kpi-value">${{ number_format($totalGananciaMuseo, 2) }}</div>
            <div class="kpi-label">Comisión Total Museo</div>
        </div>
        <div class="kpi-card k3">
            <div class="kpi-value">{{ $facturas->count() }}</div>
            <div class="kpi-label">Facturas Emitidas</div>
        </div>
    </div>

    <!-- INVOICES TABLE -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">Desglose de Facturación</div>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Nº Factura</th>
                    <th>Fecha</th>
                    <th>Obra</th>
                    <th>Precio Obra</th>
                    <th>IVA (16%)</th>
                    <th>Comisión ({{ $facturas->first()->porcentaje_ganancia ?? '—' }}%)</th>
                    <th>Total Cobrado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facturas as $factura)
                <tr>
                    <td class="fw-bold">#F-{{ str_pad($factura->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $factura->fecha_facturacion->format('d/m/Y') }}</td>
                    <td>{{ $factura->nombre_obra }}</td>
                    <td class="price-col">${{ number_format($factura->precio_obra, 2) }}</td>
                    <td class="price-col text-secondary">${{ number_format($factura->iva, 2) }}</td>
                    <td class="price-col text-info">${{ number_format($factura->precio_obra * ($factura->porcentaje_ganancia / 100), 2) }}</td>
                    <td class="price-col text-success">${{ number_format($factura->precio_venta, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No hay facturas registradas en este período.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
