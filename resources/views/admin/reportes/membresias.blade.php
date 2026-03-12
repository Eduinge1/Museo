@extends('layouts.admin')

@section('title', 'Reporte de Membresías — Museo de Arte Contemporáneo')

@section('topbar_title', 'Reporte de Membresías')
@section('topbar_subtitle', 'Registro de nuevos compradores y membresías')

@section('topbar_actions')
<button class="btn-export btn-export-xls" onclick="alert('Exportando a Excel...')"><i class="bi bi-file-earmark-spreadsheet"></i> Excel</button>
<button class="btn-export btn-export-pdf" onclick="alert('Generando PDF...')"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
@endsection

@section('extra_css')
<style>
    .filter-card { background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 1.2rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap; }
    .filter-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .filter-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #aaa; }
    .filter-ctrl { border: 1.5px solid #e0e0e0; border-radius: 10px; padding: 0.55rem 0.9rem; font-size: 0.875rem; font-family: 'DM Sans', sans-serif; color: var(--dark); background: var(--light); outline: none; min-width: 140px; transition: border-color 0.2s; cursor: pointer; }
    .btn-filter-apply { background: var(--dark); color: #fff; border: none; border-radius: 10px; padding: 0.58rem 1.3rem; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.4rem; }

    .kpi-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 2rem; }
    .kpi-card { background: #fff; border-radius: 16px; padding: 1.5rem; border: 1px solid #eee; position: relative; overflow: hidden; }
    .kpi-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 4px; }
    .k1::after { background: var(--accent-1); }
    .k2::after { background: var(--accent-3); }
    .k3::after { background: var(--accent-4); }
    .kpi-value { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--dark); line-height: 1; margin-bottom: 0.5rem; }
    .kpi-label { font-size: 0.75rem; color: #999; text-transform: uppercase; letter-spacing: 1px; }

    .panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .panel-head { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .panel-head-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); }
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table thead th { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #bbb; padding: 0.8rem 1.2rem; background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    .report-table tbody td { padding: 1rem 1.2rem; font-size: 0.875rem; border-bottom: 1px solid #f6f6f6; }
    
    .status-pill { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.7rem; font-weight: 600; border-radius: 50px; padding: 0.2rem 0.6rem; }
    .active-pill { background: rgba(6,214,160,0.1); color: #019975; }
    .inactive-pill { background: rgba(255,77,109,0.1); color: var(--accent-1); }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <form action="{{ route('admin.reportes.membresias') }}" method="GET" class="filter-card">
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

    <div class="kpi-grid">
        <div class="kpi-card k1">
            <div class="kpi-value">{{ $compradores->count() }}</div>
            <div class="kpi-label">Nuevos Compradores</div>
        </div>
        <div class="kpi-card k2">
            <div class="kpi-value">${{ number_format($totalIngresosMembresias, 2) }}</div>
            <div class="kpi-label">Ingresos por Registros</div>
        </div>
        <div class="kpi-card k3">
            <div class="kpi-value">{{ $membresiasActivas }}</div>
            <div class="kpi-label">Total Membresías Activas</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">Listado de Compradores Registrados</div>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                    <th>Estado Membresía</th>
                    <th>Monto Pagado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compradores as $comprador)
                <tr>
                    <td class="fw-bold">#{{ $comprador->id }}</td>
                    <td>{{ $comprador->user->name ?? 'N/A' }}</td>
                    <td>{{ $comprador->user->email ?? 'N/A' }}</td>
                    <td>{{ $comprador->telefono }}</td>
                    <td>{{ $comprador->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($comprador->membresias && $comprador->membresias->is_active)
                            <span class="status-pill active-pill">Activa</span>
                        @else
                            <span class="status-pill inactive-pill">Inactiva</span>
                        @endif
                    </td>
                    <td class="fw-bold">${{ number_format($comprador->membresias->monto ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No hay nuevos compradores en este período.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
