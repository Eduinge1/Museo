@extends('layouts.app')

@section('title', 'Registro — Museo de Arte Contemporáneo')

@section('content')

<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --light: #F8F5F0;
  }
  * { box-sizing: border-box; }
  body { background: var(--light); }

  /* NAVBAR */
  .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; position: sticky; top: 0; z-index: 1000; border-bottom: 2px solid var(--accent-1); }
  .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
  .navbar-brand-custom span { color: var(--accent-2); }
  .nav-link-custom { color: rgba(255,255,255,0.75); font-size: 0.875rem; font-weight: 500; text-decoration: none; margin-left: 1.5rem; transition: color 0.2s; }
  .nav-link-custom:hover { color: var(--accent-2); }
  .btn-nav-login { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.35); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; text-decoration: none; transition: border-color 0.2s, color 0.2s; margin-left: 0.75rem; }
  .btn-nav-login:hover { border-color: var(--accent-2); color: var(--accent-2); }

  /* PAGE */
  .register-wrap { max-width: 780px; margin: 3rem auto; padding: 0 1.5rem 4rem; }
  .register-title { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: var(--dark); text-align: center; margin-bottom: 0.4rem; }
  .register-sub { text-align: center; font-size: 0.875rem; color: #888; margin-bottom: 2rem; }
  .register-sub a { color: var(--accent-3); text-decoration: none; }
  .register-sub a:hover { text-decoration: underline; }

  /* STEPPER */
  .stepper { display: flex; align-items: center; margin-bottom: 2.5rem; }
  .step { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
  .step-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 700; border: 2px solid #ddd; background: #fff; color: #bbb; transition: all 0.3s; z-index: 1; }
  .step-circle.active { background: var(--accent-1); border-color: var(--accent-1); color: #fff; box-shadow: 0 4px 16px rgba(255,77,109,0.35); }
  .step-circle.done { background: var(--success, #06D6A0); border-color: var(--success, #06D6A0); color: #fff; }
  .step-label { font-size: 0.68rem; color: #bbb; margin-top: 0.4rem; text-align: center; font-weight: 500; }
  .step-label.active { color: var(--accent-1); font-weight: 700; }
  .step-label.done { color: #06D6A0; }
  .step-line { flex: 1; height: 2px; background: #e0e0e0; margin-top: -20px; transition: background 0.3s; }
  .step-line.done { background: #06D6A0; }

  /* CARD */
  .step-card { background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 4px 24px rgba(0,0,0,0.07); display: none; }
  .step-card.active { display: block; animation: fadeUp 0.4s ease; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

  .card-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 0.3rem; }
  .card-sub { font-size: 0.82rem; color: #aaa; margin-bottom: 1.5rem; }

  /* FORM */
  .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
  .form-row-1 { margin-bottom: 1rem; }
  .form-label-r { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #555; margin-bottom: 0.4rem; display: block; }
  .form-label-r .req { color: var(--accent-1); }
  .form-ctrl {
    width: 100%; border: 1.5px solid #e0e0e0; border-radius: 10px;
    padding: 0.65rem 0.9rem; font-size: 0.875rem;
    font-family: 'DM Sans', sans-serif; color: var(--dark);
    background: var(--light); outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-ctrl:focus { border-color: var(--accent-3); box-shadow: 0 0 0 3px rgba(58,134,255,0.1); background: #fff; }
  .form-ctrl::placeholder { color: #ccc; }
  .form-ctrl.is-invalid { border-color: var(--accent-1); }

  /* PASSWORD STRENGTH */
  .pwd-strength { display: flex; gap: 0.3rem; margin-top: 0.4rem; }
  .pwd-bar { height: 3px; flex: 1; border-radius: 2px; background: #eee; transition: background 0.3s; }
  .pwd-label { font-size: 0.7rem; color: #aaa; margin-top: 0.3rem; }

  /* CREDIT CARD PREVIEW */
  .card-preview-wrap { perspective: 1000px; margin-bottom: 1.5rem; }
  .card-preview {
    width: 100%; max-width: 340px; height: 200px; margin: 0 auto;
    border-radius: 18px; padding: 1.5rem;
    background: linear-gradient(135deg, var(--dark) 0%, #1a1a2e 50%, #16213e 100%);
    color: #fff; position: relative; overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    transition: transform 0.6s;
  }
  .card-preview::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background: rgba(58,134,255,0.15); }
  .card-preview::after  { content:''; position:absolute; bottom:-60px; left:-30px; width:200px; height:200px; border-radius:50%; background: rgba(255,77,109,0.1); }
  .card-chip { width: 40px; height: 30px; background: linear-gradient(135deg, var(--accent-2), #fb8500); border-radius: 6px; margin-bottom: 1.2rem; }
  .card-number-preview { font-size: 1.1rem; letter-spacing: 0.2rem; margin-bottom: 1rem; font-family: monospace; }
  .card-bottom { display: flex; justify-content: space-between; align-items: flex-end; }
  .card-holder-label { font-size: 0.6rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
  .card-holder-name { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-top: 0.2rem; }
  .card-exp-label { font-size: 0.6rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
  .card-exp-val { font-size: 0.85rem; font-weight: 600; margin-top: 0.2rem; }

  /* MEMBERSHIP NOTE */
  .membership-note { background: rgba(255,190,11,0.08); border: 1px solid rgba(255,190,11,0.25); border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.75rem; }
  .membership-note i { color: var(--accent-2); font-size: 1.2rem; flex-shrink: 0; }
  .membership-note p { font-size: 0.82rem; color: #666; margin: 0; }
  .membership-note strong { color: var(--dark); }

  /* SECURITY QUESTIONS */
  .sq-card { background: var(--light); border-radius: 12px; padding: 1rem; margin-bottom: 0.85rem; border: 1.5px solid #eee; }
  .sq-num { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--accent-4); margin-bottom: 0.5rem; }

  /* CONFIRM SUMMARY */
  .summary-row { display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0; font-size: 0.875rem; }
  .summary-row:last-child { border-bottom: none; }
  .summary-row .s-label { color: #888; }
  .summary-row .s-val { font-weight: 600; color: var(--dark); }

  /* SUCCESS */
  .success-screen { display: none; text-align: center; padding: 2rem; }
  .success-screen.show { display: block; animation: fadeUp 0.5s ease; }
  .success-ring { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #06D6A0, #118ab2); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #fff; margin: 0 auto 1.2rem; }

  /* BUTTONS */
  .btn-next { background: var(--accent-1); color: #fff; border: none; border-radius: 12px; padding: 0.75rem 2rem; font-size: 0.95rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; gap: 0.5rem; }
  .btn-next:hover { background: #e0003c; transform: translateY(-1px); }
  .btn-back-step { background: var(--light); color: #888; border: 1.5px solid #eee; border-radius: 12px; padding: 0.75rem 1.5rem; font-size: 0.875rem; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; }
  .btn-back-step:hover { border-color: #ccc; color: var(--dark); }

  /* ERRORS */
  .alert-error { background: rgba(255,77,109,0.08); border: 1px solid rgba(255,77,109,0.25); border-radius: 10px; padding: 0.85rem 1rem; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--accent-1); }

  /* FOOTER */
  .footer-mac { background: var(--dark); color: rgba(255,255,255,0.5); text-align: center; padding: 2rem; font-size: 0.82rem; border-top: 2px solid var(--accent-4); margin-top: 0; }
  .footer-mac strong { color: var(--accent-2); }

  @media(max-width:576px) { .form-row-2 { grid-template-columns: 1fr; } }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center">
  <a href="{{ route('home') }}" class="navbar-brand-custom">MAC <span>·</span> Arte</a>
  <div class="d-flex align-items-center">
    <a href="{{ route('home') }}" class="nav-link-custom">Catálogo</a>
    <a href="{{ route('login') }}" class="btn-nav-login">Iniciar sesión</a>
  </div>
</nav>

<div class="register-wrap">

  <h1 class="register-title">Crear cuenta</h1>
  <p class="register-sub">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>

  {{-- ERRORES DE VALIDACIÓN DE LARAVEL --}}
  @if($errors->any())
    <div class="alert-error">
      <i class="bi bi-exclamation-circle me-2"></i>
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif

  {{-- STEPPER --}}
  <div class="stepper" id="stepper">
    <div class="step">
      <div class="step-circle active" id="sc1">1</div>
      <div class="step-label active" id="sl1">Datos personales</div>
    </div>
    <div class="step-line" id="line1"></div>
    <div class="step">
      <div class="step-circle" id="sc2">2</div>
      <div class="step-label" id="sl2">Tarjeta</div>
    </div>
    <div class="step-line" id="line2"></div>
    <div class="step">
      <div class="step-circle" id="sc3">3</div>
      <div class="step-label" id="sl3">Seguridad</div>
    </div>
    <div class="step-line" id="line3"></div>
    <div class="step">
      <div class="step-circle" id="sc4">4</div>
      <div class="step-label" id="sl4">Confirmar</div>
    </div>
  </div>

  {{-- FORMULARIO PRINCIPAL --}}
  <form action="{{ route('register') }}" method="POST" id="registerForm">
    @csrf

    {{-- ══════════ PASO 1: DATOS PERSONALES ══════════ --}}
    <div class="step-card active" id="step1">
      <div class="card-title">Datos personales</div>
      <div class="card-sub">Completa tu información para crear tu cuenta de comprador</div>

      <div class="form-row-2">
        <div>
          <label class="form-label-r">Nombre <span class="req">*</span></label>
          <input type="text" name="name" class="form-ctrl" placeholder="Tu nombre"
                 value="{{ old('name') }}" required />
        </div>
        <div>
          <label class="form-label-r">Apellido <span class="req">*</span></label>
          <input type="text" name="apellido" class="form-ctrl" placeholder="Tu apellido"
                 value="{{ old('apellido') }}" />
        </div>
      </div>

      <div class="form-row-2">
        <div>
          <label class="form-label-r">Cédula / ID <span class="req">*</span></label>
          <input type="text" name="cedula" class="form-ctrl" placeholder="Número de identificación"
                 value="{{ old('cedula') }}" />
        </div>
        <div>
          <label class="form-label-r">Teléfono</label>
          <input type="tel" name="telefono" class="form-ctrl" placeholder="+57 300 000 0000"
                 value="{{ old('telefono') }}" />
        </div>
      </div>

      <div class="form-row-1">
        <label class="form-label-r">Correo electrónico <span class="req">*</span></label>
        <input type="email" name="email" class="form-ctrl" placeholder="tu@correo.com"
               value="{{ old('email') }}" required />
      </div>

      <div class="form-row-2">
        <div>
          <label class="form-label-r">Contraseña <span class="req">*</span></label>
          <input type="password" name="password" class="form-ctrl" id="pwdInput"
                 placeholder="Mínimo 8 caracteres" required oninput="checkPwd(this.value)" />
          <div class="pwd-strength">
            <div class="pwd-bar" id="pb1"></div>
            <div class="pwd-bar" id="pb2"></div>
            <div class="pwd-bar" id="pb3"></div>
            <div class="pwd-bar" id="pb4"></div>
          </div>
          <div class="pwd-label" id="pwdLabel">Ingresa una contraseña</div>
        </div>
        <div>
          <label class="form-label-r">Confirmar contraseña <span class="req">*</span></label>
          <input type="password" name="password_confirmation" class="form-ctrl"
                 placeholder="Repite tu contraseña" required />
        </div>
      </div>

      <div class="form-row-1">
        <label class="form-label-r">Dirección</label>
        <input type="text" name="direccion" class="form-ctrl" placeholder="Tu dirección de envío"
               value="{{ old('direccion') }}" />
      </div>

      <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn-next" onclick="goStep(2)">
          Siguiente <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

    {{-- ══════════ PASO 2: TARJETA ══════════ --}}
    <div class="step-card" id="step2">
      <div class="card-title">Método de pago</div>
      <div class="card-sub">Tu tarjeta se usará para la membresía y futuras compras</div>

      {{-- Membership note --}}
      <div class="membership-note">
        <i class="bi bi-shield-check"></i>
        <p>La membresía tiene un costo único de <strong>$10 USD</strong> que se cobrará al registrarte. Incluye acceso completo al catálogo.</p>
        <input type="hidden" name="membership_amount" value="10.00">
      </div>

      {{-- Card preview --}}
      <div class="card-preview-wrap">
        <div class="card-preview">
          <div class="card-chip"></div>
          <div class="card-number-preview" id="prevNum">•••• •••• •••• ••••</div>
          <div class="card-bottom">
            <div>
              <div class="card-holder-label">Titular</div>
              <div class="card-holder-name" id="prevName">TU NOMBRE</div>
            </div>
            <div>
              <div class="card-exp-label">Vence</div>
              <div class="card-exp-val" id="prevExp">MM/AA</div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row-1">
        <label class="form-label-r">Número de tarjeta <span class="req">*</span></label>
        <input type="text" name="card_number" class="form-ctrl" placeholder="0000 0000 0000 0000"
               maxlength="19" oninput="formatCard(this)" />
      </div>
      <div class="form-row-2">
        <div>
          <label class="form-label-r">Fecha de vencimiento <span class="req">*</span></label>
          <input type="text" name="card_expiry" class="form-ctrl" placeholder="MM/AA"
                 maxlength="5" oninput="formatExpiry(this)" />
        </div>
        <div>
          <label class="form-label-r">CVV <span class="req">*</span></label>
          <input type="text" name="card_cvv" class="form-ctrl" placeholder="•••" maxlength="4" />
        </div>
      </div>
      <div class="form-row-1">
        <label class="form-label-r">Nombre del titular <span class="req">*</span></label>
        <input type="text" name="card_holder" class="form-ctrl" placeholder="Como aparece en la tarjeta"
               oninput="document.getElementById('prevName').textContent = this.value.toUpperCase() || 'TU NOMBRE'" />
      </div>

      <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn-back-step" onclick="goStep(1)">
          <i class="bi bi-arrow-left me-1"></i> Atrás
        </button>
        <button type="button" class="btn-next" onclick="goStep(3)">
          Siguiente <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

    {{-- ══════════ PASO 3: PREGUNTAS DE SEGURIDAD ══════════ --}}
    <div class="step-card" id="step3">
      <div class="card-title">Preguntas de seguridad</div>
      <div class="card-sub">Estas preguntas te permitirán recuperar tu código de compra</div>

      @for ($i = 1; $i <= 3; $i++)
      <div class="sq-card">
        <div class="sq-num">Pregunta {{ $i }}</div>
        <div class="form-row-1">
          <label class="form-label-r">Selecciona una pregunta <span class="req">*</span></label>
          <select name="id_pregunta_{{ $i }}" class="form-ctrl" style="appearance:none;cursor:pointer" required>
            <option value="">Seleccionar...</option>
            @foreach($preguntas as $pregunta)
                <option value="{{ $pregunta->id }}">{{ $pregunta->pregunta }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-row-1" style="margin-bottom:0">
          <label class="form-label-r">Tu respuesta <span class="req">*</span></label>
          <input type="text" name="respuesta_{{ $i }}" class="form-ctrl" placeholder="Tu respuesta..." required />
        </div>
      </div>
      @endfor

      <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn-back-step" onclick="goStep(2)">
          <i class="bi bi-arrow-left me-1"></i> Atrás
        </button>
        <button type="button" class="btn-next" onclick="goStep(4)">
          Siguiente <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

    {{-- ══════════ PASO 4: CONFIRMAR ══════════ --}}
    <div class="step-card" id="step4">
      <div class="card-title">Confirmar registro</div>
      <div class="card-sub">Revisa tus datos antes de crear la cuenta</div>

      <div style="background:var(--light);border-radius:12px;padding:1.2rem;margin-bottom:1.5rem">
        <div class="summary-row">
          <span class="s-label">Nombre</span>
          <span class="s-val" id="sum_name">—</span>
        </div>
        <div class="summary-row">
          <span class="s-label">Correo</span>
          <span class="s-val" id="sum_email">—</span>
        </div>
        <div class="summary-row">
          <span class="s-label">Tarjeta</span>
          <span class="s-val" id="sum_card">—</span>
        </div>
        <div class="summary-row">
          <span class="s-label">Membresía</span>
          <span class="s-val" style="color:#06D6A0">$10.00 USD ✓</span>
        </div>
        <div class="summary-row">
          <span class="s-label">Preguntas de seguridad</span>
          <span class="s-val" style="color:#06D6A0">3 configuradas ✓</span>
        </div>
      </div>

      <div class="form-row-1">
        <div style="display:flex;align-items:flex-start;gap:0.75rem">
          <input type="checkbox" name="terminos" id="terminos" required
                 style="margin-top:3px;accent-color:var(--accent-1);width:16px;height:16px;flex-shrink:0" />
          <label for="terminos" style="font-size:0.82rem;color:#666;cursor:pointer">
            Acepto los <a href="#" style="color:var(--accent-3)">términos y condiciones</a> y autorizo el cobro de <strong>$10 USD</strong> por la membresía.
          </label>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <button type="button" class="btn-back-step" onclick="goStep(3)">
          <i class="bi bi-arrow-left me-1"></i> Atrás
        </button>
        <button type="submit" class="btn-next" style="background:var(--accent-4)">
          <i class="bi bi-check-lg"></i> Crear cuenta
        </button>
      </div>
    </div>

  </form>

  {{-- SUCCESS (se muestra si hay sesión de éxito) --}}
  @if(session('registered'))
  <div class="step-card active" style="text-align:center;padding:3rem 2rem">
    <div class="success-ring mx-auto mb-4"><i class="bi bi-check-lg" style="font-size:2rem"></i></div>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:900;margin-bottom:0.5rem">¡Cuenta creada!</h2>
    <p style="color:#888;font-size:0.875rem;margin-bottom:1.5rem">
      Te enviamos un correo de confirmación a <strong>{{ session('email') }}</strong>
    </p>
    <a href="{{ route('home') }}" class="btn-next" style="display:inline-flex;text-decoration:none">
      <i class="bi bi-house"></i> Ir al catálogo
    </a>
  </div>
  @endif

</div>

{{-- FOOTER --}}
<footer class="footer-mac">
  <p>© {{ date('Y') }} <strong>Museo de Arte Contemporáneo</strong> · Todos los derechos reservados</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let currentStep = 1;

  function goStep(n) {
    // Ocultar paso actual
    document.getElementById('step' + currentStep).classList.remove('active');

    // Actualizar stepper visual
    const sc = document.getElementById('sc' + currentStep);
    const sl = document.getElementById('sl' + currentStep);
    sc.classList.remove('active');
    sc.classList.add('done');
    sc.innerHTML = '<i class="bi bi-check"></i>';
    sl.classList.remove('active');
    sl.classList.add('done');

    if (currentStep < 4) {
      document.getElementById('line' + currentStep).classList.add('done');
    }

    // Actualizar resumen si vamos al paso 4
    if (n === 4) {
      const nameVal  = document.querySelector('[name="name"]').value;
      const emailVal = document.querySelector('[name="email"]').value;
      const cardVal  = document.querySelector('[name="card_number"]').value;
      document.getElementById('sum_name').textContent  = nameVal  || '—';
      document.getElementById('sum_email').textContent = emailVal || '—';
      document.getElementById('sum_card').textContent  = cardVal ? '•••• ' + cardVal.slice(-4) : '—';
    }

    // Mostrar nuevo paso
    currentStep = n;
    document.getElementById('step' + n).classList.add('active');
    document.getElementById('sc' + n).classList.add('active');
    document.getElementById('sl' + n).classList.add('active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Password strength
  function checkPwd(val) {
    const bars   = ['pb1','pb2','pb3','pb4'];
    const colors = ['#ff4d6d','#ffbe0b','#3a86ff','#06d6a0'];
    const labels = ['Muy débil','Débil','Buena','Fuerte'];
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    bars.forEach((id, i) => {
      document.getElementById(id).style.background = i < score ? colors[score - 1] : '#eee';
    });
    document.getElementById('pwdLabel').textContent = score > 0 ? labels[score - 1] : 'Ingresa una contraseña';
    document.getElementById('pwdLabel').style.color = score > 0 ? colors[score - 1] : '#aaa';
  }

  // Card number format
  function formatCard(input) {
    let val = input.value.replace(/\D/g,'').substring(0,16);
    input.value = val.replace(/(.{4})/g,'$1 ').trim();
    const display = val.padEnd(16,'•').replace(/(.{4})/g,'$1 ').trim();
    document.getElementById('prevNum').textContent = display;
  }

  // Expiry format
  function formatExpiry(input) {
    let val = input.value.replace(/\D/g,'').substring(0,4);
    if (val.length >= 2) val = val.slice(0,2) + '/' + val.slice(2);
    input.value = val;
    document.getElementById('prevExp').textContent = val || 'MM/AA';
  }
</script>

@endsection
