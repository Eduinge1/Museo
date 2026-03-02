@extends('layouts.app')

@section('title', 'Validación de Código — Museo de Arte Contemporáneo')

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
  .validation-card { background: #fff; color: var(--dark); border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 520px; position: relative; z-index: 2; box-shadow: 0 24px 80px rgba(0,0,0,0.5); animation: slideUp 0.5s cubic-bezier(.22,.68,0,1.2); }
  @keyframes slideUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

  /* OBRA STRIP */
  .obra-strip { background: var(--light); border-radius: 14px; padding: 1rem 1.2rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 1.8rem; border: 1px solid #eee; }
  .obra-strip-img { width: 62px; height: 62px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
  .obra-strip-img-placeholder { width: 62px; height: 62px; border-radius: 10px; background: linear-gradient(135deg, var(--accent-4), var(--accent-3)); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; }
  .obra-strip-title { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 700; }
  .obra-strip-artist { font-size: 0.78rem; color: #888; margin-top: 2px; }
  .obra-strip-price { margin-left: auto; font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--accent-1); white-space: nowrap; }

  /* HEADER */
  .val-icon-wrap { width: 64px; height: 64px; background: linear-gradient(135deg, var(--accent-3), var(--accent-4)); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #fff; margin-bottom: 1.2rem; }
  .val-label { font-size: 0.7rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; color: var(--accent-3); margin-bottom: 0.4rem; }
  .val-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 900; color: var(--dark); line-height: 1.1; margin-bottom: 0.5rem; }
  .val-subtitle { font-size: 0.875rem; color: #777; line-height: 1.6; margin-bottom: 1.8rem; }
  .val-subtitle strong { color: var(--dark); }

  /* OTP */
  .otp-label { font-size: 0.78rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; color: #555; margin-bottom: 0.8rem; }
  .otp-inputs { display: flex; gap: 0.6rem; justify-content: center; margin-bottom: 1.5rem; }
  .otp-input { width: 54px; height: 62px; border: 2px solid #ddd; border-radius: 14px; font-size: 1.6rem; font-weight: 700; font-family: 'Playfair Display', serif; text-align: center; color: var(--dark); background: var(--light); outline: none; transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s; }
  .otp-input:focus { border-color: var(--accent-3); box-shadow: 0 0 0 3px rgba(58,134,255,0.15); transform: scale(1.06); }
  .otp-input.filled  { border-color: var(--accent-4); background: #fff; }
  .otp-input.error   { border-color: var(--accent-1); background: rgba(255,77,109,0.05); animation: shake 0.4s ease; }
  .otp-input.success { border-color: #06d6a0; background: rgba(6,214,160,0.05); }
  @keyframes shake { 0%,100%{transform:translateX(0);} 20%,60%{transform:translateX(-5px);} 40%,80%{transform:translateX(5px);} }

  /* TIMER */
  .timer-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.82rem; color: #888; }
  .timer-count { font-weight: 600; color: var(--accent-3); }
  .timer-count.expired { color: var(--accent-1); }
  .btn-resend { background: none; border: none; color: var(--accent-1); font-size: 0.82rem; font-weight: 500; cursor: pointer; padding: 0; font-family: 'DM Sans', sans-serif; }
  .btn-resend:disabled { opacity: 0.35; cursor: not-allowed; color: #aaa; }
  .btn-resend:not(:disabled):hover { text-decoration: underline; }

  /* ALERTS */
  .alert-custom { border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.82rem; margin-bottom: 1.2rem; display: none; align-items: center; gap: 0.5rem; }
  .alert-custom.show { display: flex; }
  .alert-error-c   { background: rgba(255,77,109,0.08); border: 1px solid rgba(255,77,109,0.25); color: var(--accent-1); }
  .alert-success-c { background: rgba(6,214,160,0.08); border: 1px solid rgba(6,214,160,0.3); color: #019975; }

  /* VERIFY BTN */
  .btn-verify { width: 100%; background: var(--dark); color: #fff; border: none; border-radius: 12px; padding: 0.9rem; font-size: 0.95rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1rem; }
  .btn-verify:hover { background: var(--accent-3); transform: translateY(-1px); }
  .btn-verify:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }

  /* VAL FOOTER */
  .val-footer { text-align: center; font-size: 0.8rem; color: #aaa; display: flex; flex-direction: column; gap: 0.4rem; }
  .val-footer a { color: var(--accent-1); text-decoration: none; font-weight: 500; }
  .val-footer a:hover { text-decoration: underline; }

  /* SUCCESS */
  .success-screen { display: none; text-align: center; }
  .success-screen.show { display: block; animation: slideUp 0.5s ease; }
  .main-form.hidden { display: none; }
  .success-checkmark { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #06d6a0, #118ab2); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #fff; margin: 0 auto 1.2rem; }
  .success-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: var(--dark); margin-bottom: 0.75rem; }
  .success-sub { font-size: 0.875rem; color: #777; line-height: 1.7; margin-bottom: 1.8rem; }
  .btn-success { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--dark); color: #fff; border-radius: 12px; padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 500; text-decoration: none; transition: background 0.2s; font-family: 'DM Sans', sans-serif; }
  .btn-success:hover { background: var(--accent-3); color: #fff; }

  /* LOADING */
  .loading-overlay { display: none; position: fixed; inset: 0; background: rgba(13,13,13,0.85); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; color: #fff; font-size: 0.9rem; }
  .loading-overlay.show { display: flex; }
  .spinner-ring { width: 48px; height: 48px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.1); border-top-color: var(--accent-1); animation: spin 0.8s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac">
  <a href="{{ route('home') }}" class="navbar-brand-custom">Museo<span>.</span></a>
  @if(isset($obra))
    <a href="{{ route('obra.detalle', $obra->id) }}" class="btn-nav-back">
      <i class="bi bi-arrow-left me-1"></i> Volver a la obra
    </a>
  @else
    <a href="{{ route('home') }}" class="btn-nav-back">
      <i class="bi bi-arrow-left me-1"></i> Volver al catálogo
    </a>
  @endif
</nav>

{{-- LOADING --}}
<div class="loading-overlay" id="loadingOverlay">
  <div class="spinner-ring"></div>
  <p>Verificando código...</p>
</div>

<div class="page-bg">
  <div class="validation-card">

    {{-- OBRA PREVIEW --}}
    @if(isset($obra))
      <div class="obra-strip">
        @if($obra->imagen_url)
          <img src="{{ $obra->imagen_url }}" class="obra-strip-img" alt="{{ $obra->titulo }}" />
        @else
          <div class="obra-strip-img-placeholder"><i class="bi bi-image"></i></div>
        @endif
        <div>
          <div class="obra-strip-title">{{ $obra->titulo }}</div>
          <div class="obra-strip-artist">{{ $obra->artista->nombre }} · {{ $obra->genero->nombre ?? '' }}</div>
        </div>
        <div class="obra-strip-price">${{ number_format($obra->precio_venta, 0, ',', '.') }}</div>
      </div>
    @else
      {{-- Vista sin obra (acceso directo a la URL) --}}
      <div class="obra-strip">
        <div class="obra-strip-img-placeholder"><i class="bi bi-image"></i></div>
        <div>
          <div class="obra-strip-title">Obra seleccionada</div>
          <div class="obra-strip-artist">Pendiente de selección</div>
        </div>
      </div>
    @endif

    {{-- MAIN FORM --}}
    <div class="main-form" id="mainForm">

      <div class="val-icon-wrap"><i class="bi bi-shield-check"></i></div>
      <p class="val-label">Verificación de seguridad</p>
      <h1 class="val-title">Ingresa tu código</h1>
      <p class="val-subtitle">
        Enviamos un código de 6 dígitos a<br>
        <strong>{{ auth()->user() ? substr(auth()->user()->email, 0, 3) . '***@' . explode('@', auth()->user()->email)[1] : 'tu correo' }}</strong>
        — revisa tu bandeja de entrada.
      </p>

      {{-- Errores de Laravel --}}
      @if($errors->any())
        <div class="alert-custom alert-error-c show">
          <i class="bi bi-x-circle-fill"></i> {{ $errors->first() }}
        </div>
      @endif

      {{-- Formulario que envía a Laravel --}}
      <form method="POST"
            action="{{ isset($obra) ? route('catalogo.reservar', $obra->id) : '#' }}"
            id="mainFormEl">
        @csrf

        {{-- OTP inputs --}}
        <p class="otp-label">Código de verificación</p>
        <div class="otp-inputs" id="otpInputs">
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp0" />
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp1" />
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp2" />
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp3" />
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp4" />
          <input class="otp-input" type="text" maxlength="1" inputmode="numeric" id="otp5" />
        </div>
        {{-- Campo oculto que recibe el código completo --}}
        <input type="hidden" name="codigo_seguridad" id="codigoCompleto" />

        {{-- Timer --}}
        <div class="timer-row">
          <span>Código válido por: <span class="timer-count" id="timerDisplay">04:59</span></span>
          <button type="button" class="btn-resend" id="btnResend" disabled onclick="resendCode()">
            <i class="bi bi-arrow-clockwise me-1"></i> Reenviar código
          </button>
        </div>

        {{-- Alerts JS --}}
        <div class="alert-custom alert-error-c" id="errorAlert">
          <i class="bi bi-x-circle-fill"></i>
          <span id="errorMsg">Código incorrecto. Verifica e intenta de nuevo.</span>
        </div>
        <div class="alert-custom alert-success-c" id="successAlert">
          <i class="bi bi-check-circle-fill"></i>
          <span>¡Código correcto! Procesando tu compra...</span>
        </div>

        <button type="button" class="btn-verify" id="btnVerify" onclick="verifyCode()" disabled>
          Confirmar compra <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="val-footer">
        <span>¿No recibiste el código? <a href="#" onclick="resendCode(); return false;">Reenviar ahora</a></span>
        <span>¿No recuerdas tu código? <a href="{{ route('auth.recuperacion') }}">Responder preguntas de seguridad</a></span>
      </div>

    </div>

    {{-- SUCCESS SCREEN --}}
    <div class="success-screen" id="successScreen">
      <div class="success-checkmark"><i class="bi bi-check-lg"></i></div>
      <h2 class="success-title">¡Compra confirmada!</h2>
      <p class="success-sub">
        Tu adquisición de <strong>{{ isset($obra) ? $obra->titulo : 'la obra' }}</strong> ha sido registrada.<br>
        Recibirás la factura en tu correo en los próximos minutos.
      </p>
      <a href="{{ route('home') }}" class="btn-success">
        <i class="bi bi-house"></i> Volver al catálogo
      </a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const inputs    = document.querySelectorAll('.otp-input');
  const btnVerify = document.getElementById('btnVerify');

  // OTP navigation
  inputs.forEach((input, idx) => {
    input.addEventListener('input', e => {
      const val = e.target.value.replace(/\D/g, '');
      e.target.value = val;
      if (val) {
        input.classList.add('filled');
        input.classList.remove('error');
        if (idx < inputs.length - 1) inputs[idx + 1].focus();
      } else {
        input.classList.remove('filled');
      }
      checkComplete();
    });

    input.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !input.value && idx > 0) {
        inputs[idx - 1].focus();
        inputs[idx - 1].value = '';
        inputs[idx - 1].classList.remove('filled');
        checkComplete();
      }
    });

    // Paste support
    input.addEventListener('paste', e => {
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
      text.split('').forEach((char, i) => {
        if (inputs[i]) { inputs[i].value = char; inputs[i].classList.add('filled'); }
      });
      inputs[Math.min(text.length, inputs.length - 1)].focus();
      checkComplete();
    });
  });

  function checkComplete() {
    btnVerify.disabled = ![...inputs].every(i => i.value.length === 1);
  }

  function getCode() {
    return [...inputs].map(i => i.value).join('');
  }

  function verifyCode() {
    const code = getCode();
    document.getElementById('errorAlert').classList.remove('show');
    document.getElementById('successAlert').classList.remove('show');
    document.getElementById('loadingOverlay').classList.add('show');

    // Poner el código en el campo oculto
    document.getElementById('codigoCompleto').value = code;

    setTimeout(() => {
      document.getElementById('loadingOverlay').classList.remove('show');
      // Enviar el formulario a Laravel para que valide el código real
      document.getElementById('mainFormEl').submit();
    }, 1200);
  }

  // Timer countdown
  let totalSeconds = 299;
  const timerDisplay = document.getElementById('timerDisplay');
  const btnResend    = document.getElementById('btnResend');

  const timerInterval = setInterval(() => {
    totalSeconds--;
    if (totalSeconds <= 0) {
      clearInterval(timerInterval);
      timerDisplay.textContent = '00:00';
      timerDisplay.classList.add('expired');
      btnResend.disabled = false;
      return;
    }
    const m = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
    const s = String(totalSeconds % 60).padStart(2, '0');
    timerDisplay.textContent = `${m}:${s}`;
  }, 1000);

  function resendCode() {
    if (btnResend.disabled) return;
    totalSeconds = 299;
    timerDisplay.textContent = '04:59';
    timerDisplay.classList.remove('expired');
    btnResend.disabled = true;
    // Toast
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);background:var(--dark);color:#fff;padding:0.7rem 1.5rem;border-radius:50px;font-size:0.85rem;z-index:9998;border:1px solid rgba(255,255,255,0.15)';
    toast.innerHTML = '<i class="bi bi-envelope-check me-2"></i>Código reenviado a tu correo';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  // Si Laravel redirige con éxito mostrar pantalla de éxito
  @if(session('reserva_exitosa'))
    document.getElementById('mainForm').classList.add('hidden');
    document.getElementById('successScreen').classList.add('show');
  @endif
</script>

@endsection