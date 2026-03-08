
@extends('layouts.admin')

@section('title', 'Gestión de Facturas — Museo de Arte Contemporáneo')

@section('topbar_title', 'Gestión de Facturas')
@section('topbar_subtitle', 'Listado general de comprobantes emitidos')

@section('topbar_actions')
<a href="{{ route('admin.facturas.create') }}" class="btn-topbar-action text-decoration-none"><i class="bi bi-plus-lg"></i> Nueva Factura</a>
@endsection

@section('extra_css')
<style>
    .table-panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th {
      font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: 1px; color: #bbb; padding: 0.8rem 1.2rem;
      background: #fafafa; border-bottom: 1px solid #f0f0f0;
    }
    .table-custom tbody tr { border-bottom: 1px solid #f6f6f6; transition: background 0.2s; }
    .table-custom tbody tr:hover { background: #fafafa; }
    .table-custom td { padding: 1rem 1.2rem; font-size: 0.875rem; vertical-align: middle; }

    .inv-id { font-weight: 600; color: var(--dark); font-family: monospace; }
    .status-pill { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.7rem; font-weight: 600; border-radius: 50px; padding: 0.25rem 0.7rem; background: rgba(6,214,160,0.1); color: #019975; }
    .price-cell { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1rem; color: var(--dark); }
    
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fff; color: #888; transition: all 0.2s; }
    .btn-icon:hover { border-color: var(--accent-3); color: var(--accent-3); }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="table-panel">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nº Factura</th>
                    <th>Fecha</th>
                    <th>Obra</th>
                    <th>Comprador</th>
                    <th>Monto Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facturas as $factura)
                <tr>
                    <td><span class="inv-id">#F-{{ str_pad($factura->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                    <td>{{ $factura->fecha_facturacion->format('d/m/Y') }}</td>
                    <td>{{ $factura->nombre_obra }}</td>
                    <td>{{ $factura->venta->comprador->user->name ?? 'N/A' }}</td>
                    <td class="price-cell">${{ number_format($factura->precio_venta, 2) }}</td>
                    <td><span class="status-pill">Pagada</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.facturas.show', $factura->id) }}" class="btn-icon" title="Ver Detalle"><i class="bi bi-eye"></i></a>
                            <button class="btn-icon" onclick="window.print()" title="Imprimir"><i class="bi bi-printer"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">
            {{ $facturas->links() }}
        </div>
    </div>
</div>
@endsection
