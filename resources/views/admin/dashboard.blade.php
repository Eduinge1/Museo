@extends('layouts.admin')

@section('title', 'Dashboard Admin — Museo de Arte Contemporáneo')

@section('topbar_title', 'Dashboard')
@section('topbar_subtitle', 'Resumen general del museo')

@section('topbar_actions')
<a href="{{ route('admin.facturas.create') }}" class="btn-topbar-action">
  <i class="bi bi-plus-lg"></i> Nueva factura
</a>
@endsection

@section('extra_css')
<style>
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .kpi-card { background: #fff; border-radius: 16px; padding: 1.4rem 1.5rem; border: 1px solid #eee; transition: box-shadow 0.2s, transform 0.2s; position: relative; overflow: hidden; }
    .kpi-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.09); transform: translateY(-2px); }
    .kpi-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; }
    .kpi-card.k1::after { background: var(--accent-1); }
    .kpi-card.k2::after { background: var(--accent-2); }
    .kpi-card.k3::after { background: var(--success); }
    .kpi-card.k4::after { background: var(--accent-4); }
    .kpi-icon-wrap { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; margin-bottom: 1rem; }
    .k1 .kpi-icon-wrap { background: linear-gradient(135deg, var(--accent-1), #ff8fa3); }
    .k2 .kpi-icon-wrap { background: linear-gradient(135deg, var(--accent-2), #fb8500); }
    .k3 .kpi-icon-wrap { background: linear-gradient(135deg, var(--success), #118ab2); }
    .k4 .kpi-icon-wrap { background: linear-gradient(135deg, var(--accent-4), var(--accent-3)); }
    .kpi-value { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: var(--dark); line-height: 1; margin-bottom: 0.3rem; }
    .kpi-label { font-size: 0.78rem; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
    .kpi-delta { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 500; padding: 0.2rem 0.55rem; border-radius: 50px; }
    .kpi-delta.up   { background: rgba(6,214,160,0.1); color: #019975; }
    .kpi-delta.down { background: rgba(255,77,109,0.1); color: var(--accent-1); }
    .kpi-delta.neu  { background: rgba(58,134,255,0.1); color: var(--accent-3); }
    .two-col { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; margin-bottom: 1.5rem; }
    .panel { background: #fff; border-radius: 16px; border: 1px solid #eee; overflow: hidden; }
    .panel-head { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
    .panel-head-title { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--dark); }
    .panel-head-sub { font-size: 0.75rem; color: #aaa; margin-top: 1px; }
    .btn-panel-action { font-size: 0.78rem; font-weight: 500; color: var(--accent-3); background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.3rem; transition: color 0.2s; text-decoration: none; }
    .btn-panel-action:hover { color: var(--accent-4); }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom thead th { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #bbb; padding: 0.7rem 1.5rem; background: #fafafa; border-bottom: 1px solid #f0f0f0; white-space: nowrap; }
    .table-custom tbody tr { border-bottom: 1px solid #f6f6f6; transition: background 0.15s; }
    .table-custom tbody tr:last-child { border-bottom: none; }
    .table-custom tbody tr:hover { background: #fafafa; }
    .table-custom td { padding: 0.85rem 1.5rem; font-size: 0.855rem; vertical-align: middle; }
    .obra-cell { display: flex; align-items: center; gap: 0.75rem; }
    .obra-thumb { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
    .obra-thumb-placeholder { width: 40px; height: 40px; border-radius: 8px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; }
    .obra-name { font-weight: 500; color: var(--dark); font-size: 0.855rem; }
    .obra-type { font-size: 0.72rem; color: #aaa; }
    .artist-cell { font-size: 0.855rem; color: #555; }
    .price-cell { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 0.95rem; color: var(--dark); }
    .status-pill { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.7rem; font-weight: 600; border-radius: 50px; padding: 0.25rem 0.7rem; white-space: nowrap; }
    .sp-reservada { background: rgba(255,190,11,0.12); color: #b8860b; border: 1px solid rgba(255,190,11,0.3); }
    .sp-disponible { background: rgba(6,214,160,0.1); color: #019975; border: 1px solid rgba(6,214,160,0.25); }
    .sp-vendida    { background: rgba(58,134,255,0.1); color: var(--accent-3); border: 1px solid rgba(58,134,255,0.2); }
    .btn-table-confirm { background: var(--accent-1); color: #fff; border: none; border-radius: 8px; padding: 0.3rem 0.8rem; font-size: 0.75rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; white-space: nowrap; text-decoration: none; display: inline-block; }
    .btn-table-confirm:hover { background: #e0003c; transform: scale(1.04); color: #fff; }
    .btn-table-disponible { background: #888; color: #fff; border: none; border-radius: 8px; padding: 0.3rem 0.8rem; font-size: 0.75rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s; white-space: nowrap; margin-left: 4px; }
    .btn-table-disponible:hover { background: #555; }
    .donut-wrap { padding: 1.5rem; display: flex; flex-direction: column; align-items: center; }
    .donut-svg { overflow: visible; }
    .donut-label { text-anchor: middle; }
    .donut-legend { display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; margin-top: 1rem; justify-content: center; }
    .legend-item { display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; color: #666; }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
    .activity-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.85rem 1.5rem; border-bottom: 1px solid #f6f6f6; transition: background 0.15s; }
    .activity-item:last-child { border-bottom: none; }
    .activity-item:hover { background: #fafafa; }
    .act-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #fff; flex-shrink: 0; margin-top: 1px; }
    .act-text { font-size: 0.83rem; color: #444; line-height: 1.4; }
    .act-text strong { color: var(--dark); }
    .act-time { font-size: 0.7rem; color: #bbb; margin-top: 2px; }
    .member-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.8rem 1.5rem; border-bottom: 1px solid #f6f6f6; transition: background 0.15s; }
    .member-row:last-child { border-bottom: none; }
    .member-row:hover { background: #fafafa; }
    .member-avatar { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #fff; flex-shrink: 0; }
    .member-name { font-size: 0.855rem; font-weight: 500; color: var(--dark); }
    .member-date { font-size: 0.72rem; color: #aaa; }
    .member-amount { margin-left: auto; font-size: 0.82rem; font-weight: 600; color: var(--success); white-space: nowrap; }
    .mini-bars { padding: 1.2rem 1.5rem; }
    .mini-bar-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.7rem; }
    .mini-bar-label { font-size: 0.75rem; color: #888; width: 28px; flex-shrink: 0; text-align: right; }
    .mini-bar-track { flex: 1; height: 8px; background: #f0f0f0; border-radius: 50px; overflow: hidden; }
    .mini-bar-fill { height: 100%; border-radius: 50px; transition: width 1s ease; }
    .mini-bar-val { font-size: 0.75rem; color: var(--dark); font-weight: 500; white-space: nowrap; }
    @media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } .two-col { grid-template-columns: 1fr; } .three-col { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

  <!-- KPI CARDS -->
  <div class="kpi-grid">
    <div class="kpi-card k1">
      <div class="kpi-icon-wrap"><i class="bi bi-bookmark-heart"></i></div>
      <div class="kpi-value">{{ $obrasReservadasCount }}</div>
      <div class="kpi-label">Obras Reservadas</div>
      <span class="kpi-delta up"><i class="bi bi-info-circle"></i> Por confirmar</span>
    </div>
    <div class="kpi-card k2">
      <div class="kpi-icon-wrap"><i class="bi bi-currency-dollar"></i></div>
      <div class="kpi-value">${{ number_format($ingresosMes, 0) }}</div>
      <div class="kpi-label">Ingresos del Mes</div>
      <span class="kpi-delta up"><i class="bi bi-calendar-event"></i> {{ now()->monthName }}</span>
    </div>
    <div class="kpi-card k3">
      <div class="kpi-icon-wrap"><i class="bi bi-check2-circle"></i></div>
      <div class="kpi-value">{{ $obrasVendidasCount }}</div>
      <div class="kpi-label">Obras Vendidas</div>
      <span class="kpi-delta up"><i class="bi bi-bag-check"></i> Total histórico</span>
    </div>
    <div class="kpi-card k4">
      <div class="kpi-icon-wrap"><i class="bi bi-people"></i></div>
      <div class="kpi-value">{{ $membresiasActivasCount }}</div>
      <div class="kpi-label">Membresías Activas</div>
      <span class="kpi-delta neu"><i class="bi bi-person-check"></i> Usuarios PRO</span>
    </div>
  </div>

  <!-- TWO COLUMN -->
  <div class="two-col">

    <!-- OBRAS RESERVADAS TABLE -->
    <div class="panel">
      <div class="panel-head">
        <div>
          <div class="panel-head-title">Obras Reservadas — Pendientes de Confirmar</div>
          <div class="panel-head-sub">{{ $reservasPendientes->count() }} obras esperan confirmación de pago</div>
        </div>
        <a href="{{ route('admin.facturas.index') }}" class="btn-panel-action">
          Ver todas <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <table class="table-custom">
        <thead>
          <tr>
            <th>Obra</th>
            <th>Artista</th>
            <th>Precio</th>
            <th>Comprador</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservasPendientes as $reserva)
          <tr>
            <td>
              <div class="obra-cell">
                @if($reserva->obra->image_url)
                  <img src="{{ asset('storage/' . $reserva->obra->image_url) }}" class="obra-thumb" alt="">
                @else
                  <div class="obra-thumb-placeholder" style="background:linear-gradient(135deg,#ff4d6d,#ff8fa3)"><i class="bi bi-image"></i></div>
                @endif
                <div>
                  <div class="obra-name">{{ $reserva->obra->titulo }}</div>
                  <div class="obra-type">{{ $reserva->obra->genero->nombre ?? 'N/A' }}</div>
                </div>
              </div>
            </td>
            <td class="artist-cell">{{ $reserva->obra->artista->nombre ?? 'Desconocido' }}</td>
            <td class="price-cell">${{ number_format($reserva->obra->precio_venta, 0) }}</td>
            <td class="artist-cell">{{ $reserva->comprador->user->email ?? 'N/A' }}</td>
            <td><span class="status-pill sp-reservada"><i class="bi bi-circle-fill" style="font-size:0.45rem"></i> {{ $reserva->estado }}</span></td>
            <td style="white-space:nowrap;">
              <a href="{{ route('admin.facturas.create', ['venta_id' => $reserva->id]) }}"
                 class="btn-table-confirm">
                <i class="bi bi-check-circle"></i> Confirmar
              </a>
              <button type="button" class="btn-table-disponible"
                onclick="if(confirm('¿Devolver esta obra a Disponible?')){
                  document.getElementById('fdisp{{ $reserva->obra->id }}').submit();
                }">
                <i class="bi bi-arrow-counterclockwise"></i> Disponible
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="p-5 text-center text-muted">No hay reservas pendientes de confirmar</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- RIGHT PANEL -->
    <div style="display:flex; flex-direction:column; gap:1rem;">

      <!-- Estado del catálogo -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <div class="panel-head-title">Estado del Catálogo</div>
            <div class="panel-head-sub">{{ $totalObras }} obras en total</div>
          </div>
        </div>
        <div class="donut-wrap">
          @php
            $dispPct = $totalObras > 0 ? ($catalogStats['disponibles'] / $totalObras) * 100 : 0;
            $resPct  = $totalObras > 0 ? ($catalogStats['reservadas']  / $totalObras) * 100 : 0;
            $vendPct = $totalObras > 0 ? ($catalogStats['vendidas']    / $totalObras) * 100 : 0;
            $c = 345.5;
            $dispDash = ($dispPct / 100) * $c;
            $resDash  = ($resPct  / 100) * $c;
            $vendDash = ($vendPct / 100) * $c;
          @endphp
          <svg class="donut-svg" width="150" height="150" viewBox="0 0 150 150">
            <circle cx="75" cy="75" r="55" fill="none" stroke="#f0f0f0" stroke-width="18"/>
            <circle cx="75" cy="75" r="55" fill="none" stroke="#06D6A0" stroke-width="18"
              stroke-dasharray="{{ $dispDash }} {{ $c - $dispDash }}" stroke-dashoffset="0" stroke-linecap="round"/>
            <circle cx="75" cy="75" r="55" fill="none" stroke="#FFBE0B" stroke-width="18"
              stroke-dasharray="{{ $resDash }} {{ $c - $resDash }}" stroke-dashoffset="-{{ $dispDash }}" stroke-linecap="round"/>
            <circle cx="75" cy="75" r="55" fill="none" stroke="#3A86FF" stroke-width="18"
              stroke-dasharray="{{ $vendDash }} {{ $c - $vendDash }}" stroke-dashoffset="-{{ $dispDash + $resDash }}" stroke-linecap="round"/>
            <text x="75" y="70" class="donut-label" font-family="Playfair Display, serif" font-size="22" font-weight="700" fill="#0D0D0D">{{ $totalObras }}</text>
            <text x="75" y="88" class="donut-label" font-family="DM Sans, sans-serif" font-size="10" fill="#aaa">obras</text>
          </svg>
          <div class="donut-legend">
            <div class="legend-item"><div class="legend-dot" style="background:#06D6A0"></div>Disponibles ({{ $catalogStats['disponibles'] }})</div>
            <div class="legend-item"><div class="legend-dot" style="background:#FFBE0B"></div>Reservadas ({{ $catalogStats['reservadas'] }})</div>
            <div class="legend-item"><div class="legend-dot" style="background:#3A86FF"></div>Vendidas ({{ $catalogStats['vendidas'] }})</div>
          </div>
        </div>
      </div>

      <!-- Ganancias por mes -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <div class="panel-head-title">Ganancias Mensuales</div>
            <div class="panel-head-sub">Últimos 6 meses</div>
          </div>
        </div>
        <div class="mini-bars">
          @php $maxVal = collect($gananciasMensuales)->max('val') ?: 1; @endphp
          @foreach($gananciasMensuales as $gm)
          <div class="mini-bar-row">
            <span class="mini-bar-label">{{ $gm['label'] }}</span>
            <div class="mini-bar-track">
              <div class="mini-bar-fill" style="width:{{ ($gm['val'] / $maxVal) * 100 }}%;background:var(--accent-4)"></div>
            </div>
            <span class="mini-bar-val">${{ number_format($gm['val'] / 1000, 1) }}k</span>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  <!-- BOTTOM ROW -->
  <div class="three-col">

    <!-- Actividad reciente -->
    <div class="panel">
      <div class="panel-head">
        <div>
          <div class="panel-head-title">Actividad Reciente</div>
          <div class="panel-head-sub">Últimas acciones del sistema</div>
        </div>
      </div>
      @foreach($actividadReciente as $act)
      <div class="activity-item">
        <div class="act-icon" style="background:{{ $act->estado == 'Completada' ? 'var(--success)' : 'var(--accent-1)' }}">
          <i class="bi {{ $act->estado == 'Completada' ? 'bi-check-all' : 'bi-bookmark-check' }}"></i>
        </div>
        <div>
          <div class="act-text">
            <strong>{{ $act->obra->titulo }}</strong>
            {{ $act->estado == 'Completada' ? 'fue vendida a' : 'fue reservada por' }}
            {{ $act->comprador->user->email }}
          </div>
          <div class="act-time">{{ $act->created_at->diffForHumans() }}</div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Membresías recientes -->
    <div class="panel">
      <div class="panel-head">
        <div>
          <div class="panel-head-title">Membresías Recientes</div>
          <div class="panel-head-sub">Nuevos compradores registrados</div>
        </div>
      </div>
      @foreach($membresiasRecientes as $member)
      <div class="member-row">
        <div class="member-avatar" style="background:linear-gradient(135deg,var(--accent-4),var(--accent-3))">
          {{ strtoupper(substr($member->user->name, 0, 2)) }}
        </div>
        <div>
          <div class="member-name">{{ $member->user->name }}</div>
          <div class="member-date">{{ $member->created_at->format('d/m/Y, g:i a') }}</div>
        </div>
        <div class="member-amount">+${{ number_format($member->membresias->monto ?? 0, 0) }}</div>
      </div>
      @endforeach
    </div>

    <!-- Accesos rápidos -->
    <div class="panel">
      <div class="panel-head">
        <div>
          <div class="panel-head-title">Accesos Rápidos</div>
          <div class="panel-head-sub">Módulos principales del panel</div>
        </div>
      </div>
      <div style="padding: 1.2rem; display:flex; flex-direction:column; gap:0.6rem;">
        <a href="{{ route('admin.obras.index') }}" class="text-decoration-none" style="display:flex;align-items:center;gap:0.9rem;background:rgba(255,77,109,0.06);border:1px solid rgba(255,77,109,0.15);border-radius:12px;padding:0.85rem 1rem;">
          <div style="width:36px;height:36px;border-radius:9px;background:var(--accent-1);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-palette"></i></div>
          <div>
            <div style="font-size:0.875rem;font-weight:500;color:var(--dark)">Gestión de Obras</div>
            <div style="font-size:0.72rem;color:#aaa">Agregar, editar, eliminar</div>
          </div>
        </a>
        <a href="{{ route('admin.facturas.create') }}" class="text-decoration-none" style="display:flex;align-items:center;gap:0.9rem;background:rgba(6,214,160,0.06);border:1px solid rgba(6,214,160,0.2);border-radius:12px;padding:0.85rem 1rem;">
          <div style="width:36px;height:36px;border-radius:9px;background:var(--success);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-receipt"></i></div>
          <div>
            <div style="font-size:0.875rem;font-weight:500;color:var(--dark)">Módulo de Cobro</div>
            <div style="font-size:0.72rem;color:#aaa">Confirmar pagos y emitir</div>
          </div>
        </a>
        <a href="{{ route('admin.facturas.index') }}" class="text-decoration-none" style="display:flex;align-items:center;gap:0.9rem;background:rgba(58,134,255,0.06);border:1px solid rgba(58,134,255,0.2);border-radius:12px;padding:0.85rem 1rem;">
          <div style="width:36px;height:36px;border-radius:9px;background:var(--accent-3);display:flex;align-items:center;justify-content:center;color:#fff;"><i class="bi bi-file-earmark-text"></i></div>
          <div>
            <div style="font-size:0.875rem;font-weight:500;color:var(--dark)">Gestión de Facturas</div>
            <div style="font-size:0.72rem;color:#aaa">Ver todas las facturas emitidas</div>
          </div>
        </a>
      </div>
    </div>

  </div>

  {{-- FORMULARIOS OCULTOS FUERA DE LA TABLA --}}
  @foreach($reservasPendientes as $reserva)
  <form id="fdisp{{ $reserva->obra->id }}"
        action="{{ route('admin.obras.cambiarEstado', $reserva->obra->id) }}"
        method="POST" style="display:none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="estado" value="Disponible">
  </form>
  @endforeach

@endsection