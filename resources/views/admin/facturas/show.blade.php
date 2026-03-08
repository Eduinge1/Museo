@extends('layouts.admin')

@section('title', 'Factura #F-' . str_pad($factura->id, 3, '0', STR_PAD_LEFT) . ' — Museo de Arte Contemporáneo')

@section('topbar_title', 'Vista de Factura')
@section('topbar_subtitle', 'Factura #F-' . str_pad($factura->id, 3, '0', STR_PAD_LEFT) . ' · Emitida hoy')

@section('topbar_actions')
    <a href="{{ route('admin.facturas.index') }}" class="btn-action btn-back text-decoration-none"><i class="bi bi-arrow-left"></i> Volver</a>
    <button class="btn-action btn-email" onclick="showToast('Factura enviada al correo del comprador', 'success')"><i class="bi bi-envelope"></i> Enviar</button>
    <button class="btn-action btn-pdf" onclick="showToast('Generando PDF...', 'info')"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
    <button class="btn-action btn-print" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
@endsection

@section('extra_css')
<style>
    /* ─── ESTILOS EXTRAÍDOS DE TU HTML (PANTALLA 11) ─── */
    .btn-action {
      display: flex; align-items: center; gap: 0.4rem;
      border-radius: 10px; padding: 0.5rem 1rem; font-size: 0.82rem; font-weight: 500;
      font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; border: 1.5px solid;
    }
    .btn-print   { background: var(--dark); color: #fff; border-color: var(--dark); }
    .btn-print:hover { background: #333; }
    .btn-pdf     { background: var(--accent-1); color: #fff; border-color: var(--accent-1); }
    .btn-pdf:hover { background: #e0003c; }
    .btn-email   { background: transparent; color: var(--accent-3); border-color: var(--accent-3); }
    .btn-email:hover { background: var(--accent-3); color: #fff; }
    .btn-back    { background: transparent; color: #888; border-color: #ddd; }
    .btn-back:hover { border-color: #aaa; color: var(--dark); }

    /* INVOICE SELECTOR BAR */
    .invoice-bar {
      background: #fff; border-bottom: 1px solid #eee;
      padding: 0.7rem 2rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
    }
    .inv-tab {
      background: var(--light); border: 1.5px solid #eee; border-radius: 8px;
      padding: 0.35rem 0.9rem; font-size: 0.78rem; font-weight: 500; color: #888;
      cursor: pointer; transition: all 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .inv-tab.active { background: var(--dark); border-color: var(--dark); color: #fff; }
    .inv-tab:not(.active):hover { border-color: #ccc; color: var(--dark); }

    /* ─── INVOICE DOCUMENT ─── */
    .invoice-wrap { width: 100%; max-width: 820px; margin: 0 auto; }

    .invoice-doc {
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 40px rgba(0,0,0,0.12);
    }

    /* Header band */
    .inv-header-band {
      background: var(--dark);
      padding: 2rem 2.5rem;
      position: relative;
      overflow: hidden;
    }
    .inv-header-band::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse at 90% 50%, rgba(131,56,236,0.3) 0%, transparent 55%),
        radial-gradient(ellipse at 10% 80%, rgba(255,77,109,0.2) 0%, transparent 50%);
    }
    .inv-header-inner { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; }
    .inv-logo { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
    .inv-logo span { color: var(--accent-2); }
    .inv-logo-sub { font-size: 0.72rem; color: rgba(255,255,255,0.4); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
    .inv-badge-wrap { text-align: right; }
    .inv-badge {
      display: inline-block;
      background: rgba(6,214,160,0.15);
      border: 1px solid rgba(6,214,160,0.35);
      color: var(--success);
      font-size: 0.7rem; font-weight: 700;
      letter-spacing: 2px; text-transform: uppercase;
      border-radius: 50px; padding: 0.3rem 1rem;
      margin-bottom: 0.5rem;
    }
    .inv-number { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #fff; }
    .inv-date { font-size: 0.75rem; color: rgba(255,255,255,0.45); margin-top: 3px; }

    /* Color accent bar */
    .inv-accent-bar { height: 4px; background: linear-gradient(90deg, var(--accent-1) 0%, var(--accent-2) 40%, var(--accent-4) 70%, var(--accent-3) 100%); }

    /* Parties section */
    .inv-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-bottom: 1px solid #f0f0f0; }
    .inv-party { padding: 1.5rem 2.5rem; }
    .inv-party:first-child { border-right: 1px solid #f0f0f0; }
    .inv-party-label { font-size: 0.62rem; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--accent-4); margin-bottom: 0.75rem; }
    .inv-party-name { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--dark); margin-bottom: 0.3rem; }
    .inv-party-line { font-size: 0.82rem; color: #777; line-height: 1.6; }
    .inv-party-line strong { color: var(--dark); }

    /* Obra detail */
    .inv-obra-section { padding: 1.5rem 2.5rem; border-bottom: 1px solid #f0f0f0; }
    .inv-section-title { font-size: 0.62rem; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--accent-1); margin-bottom: 1rem; }

    .inv-obra-row {
      display: flex; align-items: center; gap: 1.2rem;
      background: var(--light); border-radius: 14px; padding: 1.2rem;
      border: 1px solid #eee;
    }
    .inv-obra-img {
      width: 70px; height: 70px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem; color: #fff; flex-shrink: 0;
      background: linear-gradient(135deg, var(--accent-4), var(--accent-3));
      overflow: hidden;
    }
    .inv-obra-img img { width: 100%; height: 100%; object-fit: cover; }
    
    .inv-obra-name { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--dark); }
    .inv-obra-artist { font-size: 0.82rem; color: #777; margin-top: 2px; }
    .inv-obra-tags { display: flex; gap: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap; }
    .inv-obra-tag { background: rgba(131,56,236,0.08); border: 1px solid rgba(131,56,236,0.15); color: var(--accent-4); font-size: 0.68rem; font-weight: 500; border-radius: 50px; padding: 0.15rem 0.6rem; }

    /* Tabla de conceptos */
    .inv-table-section { padding: 0 2.5rem 1.5rem; }
    .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .inv-table thead th {
      font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
      color: #bbb; padding: 0.6rem 0.75rem; background: #fafafa;
      border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0;
    }
    .inv-table thead th:first-child { border-radius: 8px 0 0 0; padding-left: 1rem; }
    .inv-table thead th:last-child { text-align: right; border-radius: 0 8px 0 0; padding-right: 1rem; }
    .inv-table tbody td { padding: 0.85rem 0.75rem; font-size: 0.855rem; border-bottom: 1px solid #f6f6f6; vertical-align: middle; }
    .inv-table tbody td:first-child { padding-left: 1rem; font-weight: 500; color: var(--dark); }
    .inv-table tbody td:last-child { text-align: right; padding-right: 1rem; font-weight: 600; color: var(--dark); }
    .inv-table tbody tr:last-child td { border-bottom: none; }
    .inv-table .desc { font-size: 0.75rem; color: #aaa; font-weight: 400; margin-top: 2px; }

    /* Totals breakdown */
    .inv-totals-section { padding: 0 2.5rem 1.5rem; display: flex; justify-content: flex-end; }
    .inv-totals-box { width: 320px; }
    .inv-total-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; font-size: 0.875rem; }
    .inv-total-row .t-label { color: #777; }
    .inv-total-row .t-value { font-weight: 500; color: var(--dark); }
    .inv-total-row.museo .t-value { color: var(--accent-4); }
    .inv-total-row.iva .t-value { color: var(--accent-2); }
    .inv-total-divider { border: none; border-top: 2px dashed #e8e8e8; margin: 0.75rem 0; }
    .inv-grand-total { display: flex; justify-content: space-between; align-items: center; background: var(--dark); border-radius: 12px; padding: 1rem 1.2rem; }
    .inv-grand-label { font-size: 0.82rem; color: rgba(255,255,255,0.6); }
    .inv-grand-value { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: #fff; }

    /* Footer */
    .inv-footer {
      padding: 1.5rem 2.5rem;
      border-top: 1px solid #f0f0f0;
      display: flex; align-items: flex-end; justify-content: space-between;
      gap: 1.5rem;
    }
    .inv-footer-museum-name { font-family: 'Playfair Display', serif; font-size: 0.9rem; font-weight: 700; color: var(--dark); }
    .inv-footer-museum-info { font-size: 0.72rem; color: #aaa; line-height: 1.6; margin-top: 2px; }

    .inv-qr {
      width: 70px; height: 70px; background: var(--light);
      border-radius: 10px; border: 1px solid #eee;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 0.2rem; flex-shrink: 0;
    }
    .inv-qr i { font-size: 1.8rem; color: #ccc; }
    .inv-qr span { font-size: 0.58rem; color: #ccc; }

    .inv-legal { font-size: 0.68rem; color: #bbb; line-height: 1.5; max-width: 260px; }

    /* PRINT STYLES */
    @media print {
      .sidebar, .topbar, .invoice-bar, .topbar-actions { display: none !important; }
      body { background: #fff; }
      .main-content { margin-left: 0 !important; }
      .page-body { padding: 0 !important; }
      .invoice-doc { box-shadow: none; border-radius: 0; }
    }
</style>
@endsection

@section('content')
<!-- INVOICE SELECTOR -->
<div class="invoice-bar mb-4 rounded-3 border">
  <span style="font-size:0.75rem;color:#aaa;margin-right:0.25rem">Facturas recientes:</span>
  @foreach($recientes as $rec)
    <a href="{{ route('admin.facturas.show', $rec->id) }}" class="inv-tab text-decoration-none {{ $rec->id == $factura->id ? 'active' : '' }}">
        F-{{ str_pad($rec->id, 3, '0', STR_PAD_LEFT) }}
    </a>
  @endforeach
</div>

<div class="invoice-wrap">
    <!-- ─── INVOICE DOCUMENT ─── -->
    <div class="invoice-doc" id="invoiceDoc">

      <!-- Header -->
      <div class="inv-header-band">
        <div class="inv-header-inner">
          <div>
            <div class="inv-logo">Museo<span>.</span></div>
            <div class="inv-logo-sub">Arte Contemporáneo</div>
            <div style="margin-top:1rem;font-size:0.75rem;color:rgba(255,255,255,0.45);line-height:1.6">
              Calle del Arte 142, Bogotá, Colombia<br>
              NIT: 900.123.456-7 · info@museo.com.co
            </div>
          </div>
          <div class="inv-badge-wrap">
            <div class="inv-badge"><i class="bi bi-check2-circle me-1"></i>PAGADA</div>
            <div class="inv-number">Factura #F-{{ str_pad($factura->id, 3, '0', STR_PAD_LEFT) }}</div>
            <div class="inv-date">Emitida el {{ $factura->fecha_facturacion->format('d \d\e M \d\e Y') }}</div>
            <div class="inv-date" style="margin-top:4px">Vence: {{ $factura->fecha_facturacion->addMonth()->format('d \d\e M \d\e Y') }}</div>
          </div>
        </div>
      </div>

      <!-- Color bar -->
      <div class="inv-accent-bar"></div>

      <!-- Parties -->
      <div class="inv-parties">
        <div class="inv-party">
          <div class="inv-party-label">Vendedor</div>
          <div class="inv-party-name">Museo de Arte Contemporáneo</div>
          <div class="inv-party-line">
            Calle del Arte 142, Bogotá<br>
            NIT: 900.123.456-7<br>
            <strong>Responsable:</strong> {{ $factura->administrador->empleado->user->name ?? 'Admin Sistema' }}<br>
            <strong>Email:</strong> admin@museo.com.co
          </div>
        </div>
        <div class="inv-party">
          <div class="inv-party-label">Comprador</div>
          <div class="inv-party-name">{{ $factura->venta->comprador->user->name ?? 'N/A' }}</div>
          <div class="inv-party-line">
            CC: 1.0{{ $factura->venta->comprador->id }}...<br>
            <strong>Email:</strong> {{ $factura->venta->comprador->user->email ?? 'N/A' }}<br>
            <strong>Tel:</strong> {{ $factura->venta->comprador->telefono ?? 'N/A' }}<br>
            <strong>Ciudad:</strong> {{ $factura->venta->direccion_envio->ciudad ?? 'N/A' }}, {{ $factura->venta->direccion_envio->pais ?? '' }}
          </div>
        </div>
      </div>

      <!-- Obra detail -->
      <div class="inv-obra-section">
        <div class="inv-section-title">Obra adquirida</div>
        <div class="inv-obra-row">
          <div class="inv-obra-img">
            @if($factura->venta->obra->image_url)
                <img src="{{ $factura->venta->obra->image_url }}" alt="">
            @else
                <i class="bi bi-image" style="color:rgba(255,255,255,0.5)"></i>
            @endif
          </div>
          <div style="flex:1">
            <div class="inv-obra-name">{{ $factura->nombre_obra }}</div>
            <div class="inv-obra-artist">{{ $factura->venta->obra->artista->nombre ?? 'Artista Desconocido' }} · {{ $factura->genero_obra }}</div>
            <div class="inv-obra-tags">
              <span class="inv-obra-tag">{{ $factura->genero_obra }}</span>
              <span class="inv-obra-tag">Certificada</span>
              <span class="inv-obra-tag">{{ $factura->fecha_facturacion->format('Y') }}</span>
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0">
            <div style="font-size:0.7rem;color:#aaa;text-transform:uppercase;letter-spacing:1px">Precio base</div>
            <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--dark)">${{ number_format($factura->precio_obra, 2) }}</div>
          </div>
        </div>
      </div>

      <!-- Tabla de conceptos -->
      <div class="inv-table-section">
        <table class="inv-table">
          <thead>
            <tr>
              <th>Concepto</th>
              <th>Descripción</th>
              <th>Base</th>
              <th>%</th>
              <th>Importe</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                Obra de arte<br>
                <span class="desc">{{ $factura->nombre_obra }} — {{ $factura->venta->obra->artista->nombre ?? 'N/A' }}</span>
              </td>
              <td style="color:#777;font-size:0.82rem">Precio de venta pactado</td>
              <td style="color:#777;font-size:0.82rem">${{ number_format($factura->precio_obra, 2) }}</td>
              <td style="color:#777;font-size:0.82rem">—</td>
              <td>${{ number_format($factura->precio_obra, 2) }}</td>
            </tr>
            <tr>
              <td>
                IVA<br>
                <span class="desc">Impuesto al Valor Agregado</span>
              </td>
              <td style="color:#777;font-size:0.82rem">Aplicado sobre precio base</td>
              <td style="color:#777;font-size:0.82rem">${{ number_format($factura->precio_obra, 2) }}</td>
              <td style="color:#777;font-size:0.82rem">16%</td>
              <td style="color:var(--accent-2)">${{ number_format($factura->iva, 2) }}</td>
            </tr>
            <tr>
              <td>
                Comisión Museo<br>
                <span class="desc">Porcentaje pactado por intermediación</span>
              </td>
              <td style="color:#777;font-size:0.82rem">Ingreso del museo</td>
              <td style="color:#777;font-size:0.82rem">${{ number_format($factura->precio_obra, 2) }}</td>
              <td style="color:#777;font-size:0.82rem">{{ $factura->porcentaje_ganancia }}%</td>
              <td style="color:var(--accent-4)">${{ number_format($factura->precio_obra * ($factura->porcentaje_ganancia/100), 2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Totals -->
      <div class="inv-totals-section">
        <div class="inv-totals-box">
          <div class="inv-total-row">
            <span class="t-label">Subtotal (precio obra)</span>
            <span class="t-value">${{ number_format($factura->precio_obra, 2) }}</span>
          </div>
          <div class="inv-total-row iva">
            <span class="t-label">IVA 16%</span>
            <span class="t-value">+ ${{ number_format($factura->iva, 2) }}</span>
          </div>
          <div class="inv-total-row museo">
            <span class="t-label">Comisión museo ({{ $factura->porcentaje_ganancia }}%)</span>
            <span class="t-value">${{ number_format($factura->precio_obra * ($factura->porcentaje_ganancia/100), 2) }}</span>
          </div>
          <hr class="inv-total-divider">
          <div class="inv-grand-total">
            <div>
              <div class="inv-grand-label">Total a cobrar al comprador</div>
              <div style="font-size:0.68rem;color:rgba(255,255,255,0.3);margin-top:2px">Incluye precio + IVA</div>
            </div>
            <div class="inv-grand-value">${{ number_format($factura->precio_venta, 2) }}</div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="inv-footer">
        <div class="inv-footer-museum">
          <div class="inv-footer-museum-name">Museo de Arte Contemporáneo</div>
          <div class="inv-footer-museum-info">
            Autorizado por: {{ $factura->administrador->empleado->user->name ?? 'Admin' }}<br>
            Esta factura es un documento legal válido conforme a la legislación tributaria vigente.
          </div>
        </div>
        <div class="inv-legal">
          Factura generada electrónicamente. El pago de esta factura implica la aceptación de los términos y condiciones del museo. Para consultas: info@museo.com.co
        </div>
        <div class="inv-qr">
          <i class="bi bi-qr-code"></i>
          <span>Verificar</span>
        </div>
      </div>

    </div>
    <!-- /invoice-doc -->
</div>
@endsection

@section('extra_js')
<script>
    function showToast(msg, type='success') {
      const c = document.getElementById('toastContainer');
      if (!c) {
          const container = document.createElement('div');
          container.id = 'toastContainer';
          container.style.cssText = 'position:fixed;bottom:2rem;right:2rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem';
          document.body.appendChild(container);
      }
      const t = document.createElement('div');
      const colors = { success: '#06D6A0', info: '#3A86FF', error: '#FF4D6D' };
      const icons  = { success: 'bi-check-circle-fill', info: 'bi-info-circle-fill', error: 'bi-x-circle-fill' };
      t.style.cssText = `background:#0D0D0D;color:#fff;border-radius:12px;padding:0.75rem 1.2rem;font-size:0.85rem;display:flex;align-items:center;gap:0.6rem;box-shadow:0 8px 30px rgba(0,0,0,0.3);animation:toastIn 0.3s ease;border-left:3px solid ${colors[type]}`;
      t.innerHTML = `<i class="bi ${icons[type]}" style="color:${colors[type]}"></i>${msg}`;
      document.getElementById('toastContainer').appendChild(t);
      setTimeout(() => t.remove(), 3500);
    }
</script>
<style>
    @keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
</style>
@endsection
