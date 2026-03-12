@extends('layouts.admin')

@section('title', 'Facturación — Museo de Arte Contemporáneo')

@section('topbar_title', 'Módulo de Facturación')
@section('topbar_subtitle', 'Confirmar pagos y emitir facturas de obras reservadas')

@section('extra_css')
<style>
    /* ─── KPI STRIP ─── */
    .kpi-strip { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 2rem; }
    .kpi-mini { background: #fff; border-radius: 14px; padding: 1.1rem 1.3rem; border: 1px solid #eee; display: flex; align-items: center; gap: 1rem; transition: box-shadow 0.2s; }
    .kpi-mini:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
    .kpi-mini-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; flex-shrink: 0; }
    .kpi-mini-val { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--dark); line-height: 1; }
    .kpi-mini-label { font-size: 0.72rem; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

    /* ─── TWO-COL LAYOUT ─── */
    .billing-grid { display: grid; grid-template-columns: 1fr 400px; gap: 1.5rem; }

    /* LEFT: reservadas list */
    .panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .panel-head { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
    .panel-head-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); }
    .panel-head-sub { font-size: 0.75rem; color: #aaa; margin-top: 1px; }

    /* Search bar inside panel */
    .panel-search { padding: 0.85rem 1.5rem; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 0.5rem; }
    .panel-search i { color: #ccc; }
    .panel-search input { flex: 1; border: none; outline: none; font-size: 0.875rem; font-family: 'DM Sans', sans-serif; color: var(--dark); background: transparent; }
    .panel-search input::placeholder { color: #ccc; }

    /* Reservation cards */
    .res-card {
      display: flex; align-items: center; gap: 1rem;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f6f6f6;
      cursor: pointer;
      transition: background 0.15s;
      position: relative;
    }
    .res-card:last-child { border-bottom: none; }
    .res-card:hover { background: #fafafa; }
    .res-card.selected { background: rgba(58,134,255,0.04); border-left: 3px solid var(--accent-3); padding-left: calc(1.5rem - 3px); }
    .res-card.processed { opacity: 0.5; pointer-events: none; }

    .res-thumb { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #fff; flex-shrink: 0; }
    .res-name { font-weight: 600; font-size: 0.875rem; color: var(--dark); }
    .res-meta { font-size: 0.72rem; color: #aaa; margin-top: 2px; }
    .res-price { margin-left: auto; font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); white-space: nowrap; text-align: right; }
    .res-buyer { font-size: 0.7rem; color: #aaa; margin-top: 2px; text-align: right; }
    .res-date { font-size: 0.68rem; color: #ccc; margin-top: 1px; text-align: right; }

    .res-done-badge { position: absolute; top: 10px; right: 12px; background: rgba(6,214,160,0.12); border: 1px solid rgba(6,214,160,0.3); color: #019975; font-size: 0.65rem; font-weight: 600; border-radius: 50px; padding: 0.15rem 0.55rem; }

    /* RIGHT: Confirmation panel */
    .confirm-panel { display: flex; flex-direction: column; gap: 1rem; }

    .confirm-box {
      background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden;
    }
    .confirm-empty {
      padding: 3rem 1.5rem; text-align: center;
      color: #ccc;
    }
    .confirm-empty i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
    .confirm-empty p { font-size: 0.875rem; }

    /* Confirm content */
    .confirm-content { display: none; }
    .confirm-content.show { display: block; }
    .confirm-empty-state { display: block; }
    .confirm-empty-state.hide { display: none; }

    .confirm-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .confirm-header-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); }
    .confirm-header-sub { font-size: 0.75rem; color: #aaa; }

    .confirm-obra-strip {
      padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0;
      display: flex; align-items: center; gap: 1rem;
    }
    .confirm-obra-img { width: 56px; height: 56px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; flex-shrink: 0; }
    .confirm-obra-name { font-weight: 600; font-size: 0.9rem; color: var(--dark); }
    .confirm-obra-artist { font-size: 0.75rem; color: #aaa; }
    .confirm-obra-price { margin-left: auto; font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--dark); }

    /* Buyer info */
    .confirm-section { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .confirm-section-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-4); margin-bottom: 0.75rem; }
    .buyer-row { display: flex; align-items: center; gap: 0.75rem; }
    .buyer-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,var(--accent-3),var(--accent-4)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.8rem; font-weight: 700; flex-shrink: 0; }
    .buyer-name { font-size: 0.875rem; font-weight: 500; color: var(--dark); }
    .buyer-email { font-size: 0.72rem; color: #aaa; }

    /* Payment method */
    .payment-row { display: flex; align-items: center; gap: 0.75rem; margin-top: 0.75rem; }
    .card-icon { width: 36px; height: 24px; background: linear-gradient(135deg,#1a1a2e,#3a86ff); border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .card-icon i { color: #fff; font-size: 0.9rem; }
    .card-num { font-size: 0.82rem; color: #555; }
    .card-exp { font-size: 0.72rem; color: #aaa; }

    /* Calc breakdown */
    .calc-section { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .calc-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.855rem; }
    .calc-row .label { color: #777; }
    .calc-row .value { font-weight: 500; color: var(--dark); }
    .calc-row.discount .value { color: var(--success); }
    .calc-divider { border: none; border-top: 1px dashed #e0e0e0; margin: 0.75rem 0; }
    .calc-total-row { display: flex; justify-content: space-between; align-items: center; }
    .calc-total-label { font-size: 0.875rem; font-weight: 600; color: var(--dark); }
    .calc-total-value { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--accent-1); }

    /* Commission selector */
    .commission-select-wrap { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem; }
    .commission-label { font-size: 0.78rem; color: #777; }
    .commission-select { border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.82rem; font-family: 'DM Sans', sans-serif; color: var(--dark); background: var(--light); outline: none; cursor: pointer; transition: border-color 0.2s; }
    .commission-select:focus { border-color: var(--accent-3); }

    /* Confirm button */
    .confirm-actions { padding: 1.2rem 1.5rem; }
    .btn-confirm {
      width: 100%; background: var(--success); color: #fff; border: none; border-radius: 12px;
      padding: 0.9rem; font-size: 0.95rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
      cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 0.5rem;
      margin-bottom: 0.6rem;
    }
    .btn-confirm:hover { background: #04b886; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(6,214,160,0.3); }
    .btn-confirm:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
    .btn-preview-invoice {
      width: 100%; background: transparent; color: var(--accent-3); border: 1.5px solid var(--accent-3);
      border-radius: 12px; padding: 0.7rem; font-size: 0.875rem; font-weight: 500;
      font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-preview-invoice:hover { background: var(--accent-3); color: #fff; }

    /* History panel */
    .history-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1.5rem; border-bottom: 1px solid #f6f6f6; transition: background 0.15s; }
    .history-item:last-child { border-bottom: none; }
    .history-item:hover { background: #fafafa; }
    .h-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #fff; flex-shrink: 0; }
    .h-num { font-size: 0.82rem; font-weight: 600; color: var(--dark); }
    .h-meta { font-size: 0.72rem; color: #aaa; margin-top: 1px; }
    .h-amount { margin-left: auto; font-weight: 600; font-size: 0.875rem; color: var(--success); white-space: nowrap; }
    .h-date { font-size: 0.68rem; color: #ccc; text-align: right; margin-top: 2px; }

    @media(max-width:1100px){ .billing-grid{grid-template-columns:1fr;} .kpi-strip{grid-template-columns:repeat(2,1fr);} }
</style>
@endsection

@section('content')
      <!-- KPI STRIP -->
      <div class="kpi-strip">
        <div class="kpi-mini">
          <div class="kpi-mini-icon" style="background:linear-gradient(135deg,var(--accent-2),#fb8500)"><i class="bi bi-bookmark-heart"></i></div>
          <div><div class="kpi-mini-val">{{ $totalReservadas }}</div><div class="kpi-mini-label">Reservadas</div></div>
        </div>
        <div class="kpi-mini">
          <div class="kpi-mini-icon" style="background:linear-gradient(135deg,var(--accent-1),#ff8fa3)"><i class="bi bi-hourglass-split"></i></div>
          <div><div class="kpi-mini-val">{{ $reservas->count() }}</div><div class="kpi-mini-label">Pendientes</div></div>
        </div>
        <div class="kpi-mini">
          <div class="kpi-mini-icon" style="background:linear-gradient(135deg,var(--success),#118ab2)"><i class="bi bi-receipt-cutoff"></i></div>
          <div><div class="kpi-mini-val">{{ $totalFacturadas }}</div><div class="kpi-mini-label">Facturas emitidas</div></div>
        </div>
        <div class="kpi-mini">
          <div class="kpi-mini-icon" style="background:linear-gradient(135deg,var(--accent-4),var(--accent-3))"><i class="bi bi-currency-dollar"></i></div>
          <div><div class="kpi-mini-val">${{ number_format($ingresosMes, 0) }}</div><div class="kpi-mini-label">Ingresos mes</div></div>
        </div>
      </div>

      <!-- BILLING GRID -->
      <div class="billing-grid">

        <!-- LEFT: Lista de reservadas -->
        <div style="display:flex;flex-direction:column;gap:1rem;">
          <div class="panel">
            <div class="panel-head">
              <div>
                <div class="panel-head-title">Obras Reservadas — Pendientes</div>
                <div class="panel-head-sub">Selecciona una obra para confirmar el pago y emitir factura</div>
              </div>
            </div>
            <div class="panel-search">
              <i class="bi bi-search"></i>
              <input type="text" placeholder="Buscar por obra o comprador..." id="searchInput" oninput="filterReservas()" />
            </div>
            <div id="reservasList">
              @forelse($reservas as $reserva)
              <div class="res-card" onclick="selectReserva({{ json_encode($reserva) }})" id="card_{{ $reserva->id }}">
                <div class="res-thumb" style="background:linear-gradient(135deg, var(--accent-1), var(--accent-4))"><i class="bi bi-image"></i></div>
                <div style="flex:1;min-width:0">
                  <div class="res-name">{{ $reserva->obra->titulo }}</div>
                  <div class="res-meta">{{ $reserva->obra->artista->nombre ?? 'N/A' }} · {{ $reserva->obra->genero->nombre ?? 'N/A' }}</div>
                </div>
                <div>
                  <div class="res-price">${{ number_format($reserva->obra->precio_venta, 0) }}</div>
                  <div class="res-buyer">{{ $reserva->comprador->user->name ?? 'N/A' }}</div>
                  <div class="res-date">{{ \Carbon\Carbon::parse($reserva->fecha_venta)->diffForHumans() }}</div>
                </div>
              </div>
              @empty
              <div class="p-4 text-center text-muted">No hay obras reservadas actualmente.</div>
              @endforelse
            </div>
          </div>

          <!-- Historial reciente -->
          <div class="panel">
            <div class="panel-head">
              <div>
                <div class="panel-head-title">Últimas Facturas Emitidas</div>
                <div class="panel-head-sub">Historial reciente del módulo</div>
              </div>
            </div>
            @foreach($ultimasFacturas as $factura)
            <a href="{{ route('admin.facturas.show', $factura->id) }}" class="history-item text-decoration-none">
              <div class="h-icon" style="background:linear-gradient(135deg,var(--success),#118ab2)"><i class="bi bi-receipt"></i></div>
              <div>
                  <div class="h-num">Factura #{{ str_pad($factura->id, 3, '0', STR_PAD_LEFT) }}</div>
                  <div class="h-meta">{{ $factura->nombre_obra }} · {{ $factura->venta->comprador->user->name ?? 'N/A' }}</div>
              </div>
              <div style="text-align:right;margin-left:auto">
                  <div class="h-amount">${{ number_format($factura->precio_venta, 0) }}</div>
                  <div class="h-date">{{ $factura->fecha_facturacion->format('d/m/Y h:i a') }}</div>
              </div>
            </a>
            @endforeach          </div>
        </div>

        <!-- RIGHT: Confirm & Calculate -->
        <div class="confirm-panel">
          <div class="confirm-box">
            <!-- EMPTY STATE -->
            <div class="confirm-empty confirm-empty-state" id="confirmEmpty">
              <i class="bi bi-receipt-cutoff"></i>
              <p>Selecciona una obra reservada<br>para ver el detalle y confirmar</p>
            </div>

            <!-- FILLED STATE -->
            <div class="confirm-content" id="confirmContent">
              <div class="confirm-header">
                <div class="confirm-header-title">Confirmar Pago</div>
                <div class="confirm-header-sub">Revisa los datos antes de emitir la factura</div>
              </div>

              <!-- Obra -->
              <div class="confirm-obra-strip">
                <div class="confirm-obra-img" id="c_img" style="background:linear-gradient(135deg,#ff4d6d,#ff8fa3)"><i class="bi bi-image"></i></div>
                <div>
                  <div class="confirm-obra-name" id="c_nombre">—</div>
                  <div class="confirm-obra-artist" id="c_artista">—</div>
                </div>
                <div class="confirm-obra-price" id="c_precio">—</div>
              </div>

              <!-- Buyer -->
              <div class="confirm-section">
                <div class="confirm-section-label">Datos del comprador</div>
                <div class="buyer-row">
                  <div class="buyer-avatar" id="c_avatar">??</div>
                  <div>
                    <div class="buyer-name" id="c_buyerName">—</div>
                    <div class="buyer-email" id="c_buyerEmail">—</div>
                  </div>
                </div>
                <div class="payment-row">
                  <div class="card-icon"><i class="bi bi-credit-card-2-front"></i></div>
                  <div>
                    <div class="card-num">•••• •••• •••• ????</div>
                    <div class="card-exp">Método registrado</div>
                  </div>
                </div>
              </div>

              <!-- Calc -->
              <form action="{{ route('admin.facturas.store') }}" method="POST" id="billingForm">
                @csrf
                <input type="hidden" name="id_obra" id="inp_id_obra">
                <input type="hidden" name="id_comprador" id="inp_id_comprador">
                <input type="hidden" name="id_direccion_envio" id="inp_id_direccion_envio">

                <div class="calc-section">
                    <div class="confirm-section-label">Desglose de pago</div>
                    <div class="calc-row"><span class="label">Precio base</span><span class="value" id="c_base">—</span></div>
                    <div class="calc-row"><span class="label">IVA (16%)</span><span class="value" id="c_iva">—</span></div>
                    <div class="commission-select-wrap">
                    <span class="commission-label">Comisión museo:</span>
                    <select name="porcentaje_ganancia" class="commission-select" id="commissionSelect" onchange="recalc()">
                        <option value="5">5%</option>
                        <option value="6">6%</option>
                        <option value="7">7%</option>
                        <option value="8">8%</option>
                        <option value="9">9%</option>
                        <option value="10" selected>10%</option>
                    </select>
                    <span class="value" id="c_comision" style="font-size:0.875rem;font-weight:500;margin-left:auto">—</span>
                    </div>
                    <hr class="calc-divider">
                    <div class="calc-total-row">
                    <span class="calc-total-label">Total a cobrar</span>
                    <span class="calc-total-value" id="c_total">—</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="confirm-actions">
                    <button type="submit" class="btn-confirm" id="btnConfirm">
                    <i class="bi bi-check2-circle"></i> Confirmar pago y emitir factura
                    </button>
                </div>
                @if ($errors->any())
    <div class="alert alert-danger" style="background-color: #ffcccc; color: red; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>¡Uy! Laravel rechazó el formulario por esto:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" style="background-color: #ffcccc; color: red; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
@endif
              </form>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('extra_js')
<script>
    let selectedReserva = null;

    window.onload = function() {
        const ventaId = "{{ $venta_id ?? '' }}";
        if (ventaId) {
            const card = document.getElementById('card_' + ventaId);
            if (card) {
                card.click();
            }
        }
    };

    function selectReserva(reserva) {
      selectedReserva = reserva;

      // Show confirm panel
      document.getElementById('confirmEmpty').classList.add('hide');
      document.getElementById('confirmContent').classList.add('show');

      // Fill data
      document.getElementById('c_nombre').textContent     = reserva.obra.titulo;
      document.getElementById('c_artista').textContent    = reserva.obra.artista.nombre + ' · ' + (reserva.obra.genero ? reserva.obra.genero.nombre : 'N/A');
      document.getElementById('c_precio').textContent     = '$' + reserva.obra.precio_venta.toLocaleString();
      document.getElementById('c_buyerName').textContent  = reserva.comprador.user.name;
      document.getElementById('c_buyerEmail').textContent = reserva.comprador.user.email;
      document.getElementById('c_avatar').textContent     = reserva.comprador.user.name.substring(0, 2).toUpperCase();
      document.getElementById('c_base').textContent       = '$' + reserva.obra.precio_venta.toLocaleString();

      // Inputs
      document.getElementById('inp_id_obra').value = reserva.obra.id;
      document.getElementById('inp_id_comprador').value = reserva.comprador.id;
      document.getElementById('inp_id_direccion_envio').value = reserva.id_direccion_envio || 1;

      recalc();

      // Highlight selected
      document.querySelectorAll('.res-card').forEach(c => c.classList.remove('selected'));
      document.getElementById('card_' + reserva.id).classList.add('selected');
    }

    function recalc() {
      if (!selectedReserva) return;
      const precio = selectedReserva.obra.precio_venta;
      const com    = parseFloat(document.getElementById('commissionSelect').value) / 100;
      const iva    = precio * 0.16;
      const comVal = precio * com;
      const total  = precio + iva;

      document.getElementById('c_iva').textContent      = '$' + iva.toLocaleString(undefined, {minimumFractionDigits: 2});
      document.getElementById('c_comision').textContent = '$' + comVal.toLocaleString(undefined, {minimumFractionDigits: 2});
      document.getElementById('c_total').textContent    = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    function filterReservas() {
      const q = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('.res-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(q) ? 'flex' : 'none';
      });
    }
</script>
@endsection
