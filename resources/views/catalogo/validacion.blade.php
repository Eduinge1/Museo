@extends('layouts.app')

@section('title', 'Validación de Compra — Museo de Arte Contemporáneo')

@section('content')

<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --light: #F8F5F0;
  }
  * { box-sizing: border-box; }
  body { background: var(--dark); }

  /* NAVBAR */
  .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; border-bottom: 2px solid var(--accent-1); display: flex; align-items: center; justify-content: space-between; }
  .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
  .navbar-brand-custom span { color: var(--accent-2); }
  .btn-nav-back { background: transparent; color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s; }
  .btn-nav-back:hover { border-color: var(--accent-2); color: var(--accent-2); }

  /* BG */
  .page-bg { min-height: calc(100vh - 65px); display: flex; align-items: center; justify-content: center; position: relative; padding: 3rem 1rem; }
  .page-bg::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 20% 30%, rgba(58,134,255,0.2) 0%, transparent 55%), radial-gradient(ellipse at 80% 70%, rgba(255,77,109,0.18) 0%, transparent 55%), radial-gradient(ellipse at 50% 50%, rgba(131,56,236,0.1) 0%, transparent 70%); pointer-events: none; }

  /* CARD */
  .validation-card { background: #fff; color: var(--dark); border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 600px; position: relative; z-index: 2; box-shadow: 0 24px 80px rgba(0,0,0,0.5); animation: slideUp 0.5s cubic-bezier(.22,.68,0,1.2); }
  @keyframes slideUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

  /* OBRA STRIP */
  .obra-strip { background: var(--light); border-radius: 14px; padding: 1rem 1.2rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 1.8rem; border: 1px solid #eee; }
  .obra-strip-img { width: 62px; height: 62px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
  .obra-strip-img-placeholder { width: 62px; height: 62px; border-radius: 10px; background: linear-gradient(135deg, var(--accent-4), var(--accent-3)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; }
  .obra-strip-title { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 700; }
  .obra-strip-artist { font-size: 0.78rem; color: #888; margin-top: 2px; }
  .obra-strip-price { margin-left: auto; font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--accent-1); white-space: nowrap; }

  /* FORM SECTIONS */
  .section-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-3); margin-bottom: 1rem; display: block; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
  .form-control-custom { width: 100%; border: 1.5px solid #ddd; border-radius: 12px; padding: 0.6rem 1rem; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; background: #fff; margin-bottom: 1rem; outline: none; transition: border-color 0.2s; }
  .form-control-custom:focus { border-color: var(--accent-3); }

  /* OTP */
  .otp-inputs { display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 1.5rem; }
  .otp-input { width: 45px; height: 55px; border: 2px solid #ddd; border-radius: 12px; font-size: 1.4rem; font-weight: 700; text-align: center; background: var(--light); outline: none; transition: all 0.2s; }
  .otp-input:focus { border-color: var(--accent-3); transform: scale(1.05); background: #fff; }
  .otp-input.filled { border-color: var(--accent-4); }

  /* BUTTONS */
  .btn-verify { width: 100%; background: var(--dark); color: #fff; border: none; border-radius: 12px; padding: 0.9rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-verify:hover { background: var(--accent-3); transform: translateY(-1px); }
  .btn-verify:disabled { opacity: 0.5; cursor: not-allowed; }

  .success-screen { display: none; text-align: center; }
  .success-screen.show { display: block; animation: slideUp 0.5s ease; }
  .main-form.hidden { display: none; }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac">
  <a href="{{ route('home') }}" class="navbar-brand-custom">Museo<span>.</span></a>
  <a href="{{ route('home') }}" class="btn-nav-back"><i class="bi bi-arrow-left"></i> Catálogo</a>
</nav>

{{-- LOADING --}}
<div class="loading-overlay" id="loadingOverlay" style="display:none;">
  <div class="spinner-ring"></div>
  <p>Procesando reserva...</p>
</div>

<div class="page-bg">
  <div class="validation-card">

    {{-- OBRA PREVIEW --}}
    <div class="obra-strip">
      @if($obra->image_url)
        <img src="{{ asset($obra->image_url) }}" class="obra-strip-img" alt="{{ $obra->titulo }}" />
      @else
        <div class="obra-strip-img-placeholder"><i class="bi bi-image"></i></div>
      @endif
      <div>
        <div class="obra-strip-title">{{ $obra->titulo }}</div>
        <div class="obra-strip-artist">{{ $obra->artista->nombre }}</div>
      </div>
      <div class="obra-strip-price">${{ number_format($obra->precio_venta, 0) }}</div>
    </div>

    {{-- MAIN FORM --}}
    <div class="main-form" id="mainForm">
      
      @if($errors->any())
        <div class="alert alert-danger rounded-4 small py-2 mb-3">
          <i class="bi bi-exclamation-circle me-2"></i> {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('catalogo.reservar', $obra->id) }}" id="reservaForm">
        @csrf

        {{-- SHIPPING SECTION --}}
        <span class="section-label">Dirección de Envío</span>
        <div class="row">
          <div class="col-md-6">
            <input type="text" name="pais" class="form-control-custom" placeholder="País" required value="Venezuela">
          </div>
          <div class="col-md-6">
            <input type="text" name="estado_provincia" class="form-control-custom" placeholder="Estado / Provincia" required>
          </div>
          <div class="col-md-6">
            <input type="text" name="ciudad" class="form-control-custom" placeholder="Ciudad" required>
          </div>
          <div class="col-md-6">
            <input type="text" name="parroquia" class="form-control-custom" placeholder="Parroquia" required>
          </div>
          <div class="col-12">
            <input type="text" name="calle" class="form-control-custom" placeholder="Calle / Avenida / Edificio" required>
          </div>
        </div>

        {{-- SECURITY SECTION --}}
        <span class="section-label">Verificación de Seguridad</span>
        <p class="small text-muted mb-3">Ingresa tu código de seguridad de 6 dígitos para confirmar.</p>
        
        <div class="otp-inputs">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp0">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp1">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp2">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp3">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp4">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp5">
        </div>
        <input type="hidden" name="codigo_seguridad" id="codigoCompleto">

        <button type="button" class="btn-verify" id="btnSubmit" onclick="submitForm()" disabled>
          Confirmar Reserva y Dirección <i class="bi bi-check2-circle"></i>
        </button>
      </form>

      <div class="text-center mt-3 small">
        <a href="{{ route('auth.recuperacion') }}" class="text-muted text-decoration-none">¿No recuerdas tu código?</a>
      </div>
    </div>

    {{-- SUCCESS SCREEN (Optional if you want to handle it with JS instead of redirect) --}}
    @if(session('success'))
    <div class="success-screen show">
      <div class="success-checkmark"><i class="bi bi-check-lg"></i></div>
      <h2 class="success-title">¡Reserva Exitosa!</h2>
      <p class="success-sub">La obra ha sido reservada y la dirección de envío registrada.</p>
      <a href="{{ route('home') }}" class="btn-success">Volver al Inicio</a>
    </div>
    <script>document.getElementById('mainForm').style.display = 'none';</script>
    @endif

  </div>
</div>

<script>
  const inputs = document.querySelectorAll('.otp-input');
  const btn = document.getElementById('btnSubmit');

  inputs.forEach((input, idx) => {
    input.addEventListener('input', e => {
      const val = e.target.value.replace(/\D/g, '');
      e.target.value = val;
      if (val) {
        input.classList.add('filled');
        if (idx < inputs.length - 1) inputs[idx + 1].focus();
      }
      checkComplete();
    });
    input.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !input.value && idx > 0) {
        inputs[idx-1].focus();
      }
    });
  });

  function checkComplete() {
    const isFilled = [...inputs].every(i => i.value.length === 1);
    btn.disabled = !isFilled;
  }

  function submitForm() {
    const code = [...inputs].map(i => i.value).join('');
    document.getElementById('codigoCompleto').value = code;
    document.getElementById('loadingOverlay').style.display = 'flex';
    document.getElementById('reservaForm').submit();
  }
</script>

@endsection