@extends('layouts.app')

@section('title', 'Recuperación de Código — Museo de Arte Contemporáneo')

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

  /* PAGE BG */
  .page-bg { min-height: calc(100vh - 65px); display: flex; align-items: center; justify-content: center; position: relative; padding: 3rem 1rem; }
  .page-bg::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 15% 25%, rgba(255,190,11,0.15) 0%, transparent 55%), radial-gradient(ellipse at 85% 70%, rgba(131,56,236,0.2) 0%, transparent 55%), radial-gradient(ellipse at 50% 90%, rgba(255,77,109,0.12) 0%, transparent 60%); pointer-events: none; }

  /* CARD */
  .recovery-card { background: #fff; color: var(--dark); border-radius: 24px; width: 100%; max-width: 560px; position: relative; z-index: 2; box-shadow: 0 24px 80px rgba(0,0,0,0.5); overflow: hidden; animation: slideUp 0.5s cubic-bezier(.22,.68,0,1.2); }
  @keyframes slideUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

  /* PROGRESS */
  .progress-bar-top { height: 4px; background: #eee; }
  .progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--accent-2), var(--accent-1)); border-radius: 0 4px 4px 0; transition: width 0.4s ease; }

  /* STEPS INDICATOR */
  .steps-indicator { display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.2rem 2rem 0; }
  .step-dot { width: 28px; height: 28px; border-radius: 50%; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 600; color: #bbb; transition: all 0.3s; flex-shrink: 0; }
  .step-dot.active { border-color: var(--accent-3); color: var(--accent-3); background: rgba(58,134,255,0.08); }
  .step-dot.done { border-color: #06d6a0; background: #06d6a0; color: #fff; }
  .step-line { flex: 1; height: 1px; background: #eee; max-width: 40px; }
  .step-line.done { background: #06d6a0; }

  .card-body-pad { padding: 1.5rem 2.5rem 2.5rem; }

  /* HEADER */
  .rec-icon-wrap { width: 58px; height: 58px; background: linear-gradient(135deg, var(--accent-2), var(--accent-1)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #fff; margin-bottom: 1rem; }
  .rec-label { font-size: 0.7rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; color: var(--accent-2); margin-bottom: 0.3rem; }
  .rec-title { font-family: 'Playfair Display', serif; font-size: 1.65rem; font-weight: 900; color: var(--dark); line-height: 1.15; margin-bottom: 0.4rem; }
  .rec-subtitle { font-size: 0.86rem; color: #888; line-height: 1.6; margin-bottom: 1.8rem; }

  /* FORM */
  .form-label-custom { font-size: 0.78rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; color: #555; margin-bottom: 0.4rem; display: block; }
  .input-wrap { position: relative; margin-bottom: 1.3rem; }
  .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 1rem; pointer-events: none; }
  .form-control-custom { width: 100%; border: 1.5px solid #ddd; border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2.6rem; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--dark); outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
  .form-control-custom:focus { border-color: var(--accent-3); box-shadow: 0 0 0 3px rgba(58,134,255,0.12); }
  .form-control-custom::placeholder { color: #bbb; }

  /* QUESTION CARDS */
  .question-card { background: var(--light); border-radius: 14px; padding: 1.2rem 1.3rem; margin-bottom: 1rem; border: 1.5px solid #eee; transition: border-color 0.2s; }
  .question-card.answered { border-color: rgba(6,214,160,0.4); background: rgba(6,214,160,0.03); }
  .question-card.wrong { border-color: rgba(255,77,109,0.35); background: rgba(255,77,109,0.03); animation: shake 0.4s ease; }
  @keyframes shake { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-5px)} 40%,80%{transform:translateX(5px)} }
  .question-num { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--accent-4); margin-bottom: 0.3rem; }
  .question-text { font-size: 0.88rem; font-weight: 500; color: var(--dark); margin-bottom: 0.75rem; }
  .question-input { width: 100%; border: 1.5px solid #ddd; border-radius: 10px; padding: 0.65rem 1rem; font-size: 0.88rem; font-family: 'DM Sans', sans-serif; background: #fff; color: var(--dark); outline: none; transition: border-color 0.2s; }
  .question-input:focus { border-color: var(--accent-3); box-shadow: 0 0 0 3px rgba(58,134,255,0.1); }
  .question-status { font-size: 0.75rem; margin-top: 0.4rem; display: none; align-items: center; gap: 0.3rem; }
  .question-status.show { display: flex; }
  .status-ok { color: #019975; }
  .status-fail { color: var(--accent-1); }

  /* ALERTS */
  .alert-custom { border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.82rem; margin-bottom: 1.2rem; display: none; align-items: center; gap: 0.5rem; }
  .alert-custom.show { display: flex; }
  .alert-error   { background: rgba(255,77,109,0.08); border: 1px solid rgba(255,77,109,0.25); color: var(--accent-1); }
  .alert-info    { background: rgba(58,134,255,0.07); border: 1px solid rgba(58,134,255,0.2); color: var(--accent-3); }
  .alert-success { background: rgba(6,214,160,0.08); border: 1px solid rgba(6,214,160,0.3); color: #019975; }

  /* BUTTONS */
  .btn-primary-custom { width: 100%; background: var(--dark); color: #fff; border: none; border-radius: 12px; padding: 0.9rem; font-size: 0.95rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1rem; }
  .btn-primary-custom:hover { background: var(--accent-4); transform: translateY(-1px); }
  .btn-primary-custom:disabled { opacity: 0.45; cursor: not-allowed; transform: none !important; }
  .footer-link-row { text-align: center; font-size: 0.82rem; color: #aaa; }
  .footer-link-row a { color: var(--accent-3); text-decoration: none; }
  .footer-link-row a:hover { text-decoration: underline; }

  /* STEP PANELS */
  .step-panel { display: none; }
  .step-panel.active { display: block; }

  /* NEW CODE BOX */
  .new-code-box { background: var(--dark); border-radius: 16px; padding: 1.5rem; margin: 1.5rem 0; text-align: center; }
  .new-code-label { font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 0.75rem; }
  .new-code-value { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; color: transparent; letter-spacing: 0.5rem; background: rgba(255,255,255,0.1); border-radius: 10px; padding: 0.75rem; margin-bottom: 1rem; user-select: none; filter: blur(8px); transition: filter 0.4s; }
  .new-code-value.revealed { color: var(--accent-2); background: transparent; filter: blur(0); }
  .btn-reveal { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.82rem; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.2s; margin-bottom: 0.75rem; }
  .btn-reveal:hover { background: rgba(255,255,255,0.2); }
  .code-warning { font-size: 0.75rem; color: rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; gap: 0.3rem; }

  /* SUCCESS */
  .success-checkmark { width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, var(--accent-2), var(--accent-1)); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #fff; margin: 0 auto 1.2rem; }
  .success-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 900; color: var(--dark); margin-bottom: 0.75rem; text-align: center; }
  .success-sub { font-size: 0.875rem; color: #777; line-height: 1.7; margin-bottom: 1.5rem; text-align: center; }
  .btn-goto-login { display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: var(--dark); color: #fff; border-radius: 12px; padding: 0.85rem 2rem; font-size: 0.95rem; text-decoration: none; transition: background 0.2s; font-family: 'DM Sans', sans-serif; width: 100%; }
  .btn-goto-login:hover { background: var(--accent-4); color: #fff; }

  /* LOADING */
  .loading-overlay { display: none; position: fixed; inset: 0; background: rgba(13,13,13,0.85); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; color: #fff; font-size: 0.9rem; }
  .loading-overlay.show { display: flex; }
  .spinner-ring { width: 48px; height: 48px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.1); border-top-color: var(--accent-2); animation: spin 0.8s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac">
  <a href="{{ route('home') }}" class="navbar-brand-custom">Museo<span>.</span></a>
  <a href="{{ route('login') }}" class="btn-nav-back">
    <i class="bi bi-arrow-left me-1"></i> Volver al login
  </a>
</nav>

{{-- LOADING --}}
<div class="loading-overlay" id="loadingOverlay">
  <div class="spinner-ring"></div>
  <p id="loadingMsg">Buscando cuenta...</p>
</div>

<div class="page-bg">
  <div class="recovery-card">

    {{-- PROGRESS BAR --}}
    <div class="progress-bar-top">
      <div class="progress-bar-fill" id="progressFill" style="width:33%"></div>
    </div>

    {{-- STEPS DOTS --}}
    <div class="steps-indicator">
      <div class="step-dot active" id="dot1">1</div>
      <div class="step-line" id="line1"></div>
      <div class="step-dot" id="dot2">2</div>
      <div class="step-line" id="line2"></div>
      <div class="step-dot" id="dot3">3</div>
    </div>

    <div class="card-body-pad">

      {{-- ══════ STEP 1: CORREO ══════ --}}
      <div class="step-panel active" id="step1">
        <div class="rec-icon-wrap"><i class="bi bi-envelope-open"></i></div>
        <p class="rec-label">Paso 1 de 3</p>
        <h1 class="rec-title">Recuperar código</h1>
        <p class="rec-subtitle">Ingresa el correo con el que te registraste para verificar tu identidad.</p>

        <label class="form-label-custom">Correo electrónico</label>
        <div class="input-wrap">
          <i class="bi bi-envelope input-icon"></i>
          <input type="email" class="form-control-custom" id="emailField"
                 placeholder="tu@correo.com"
                 value="{{ auth()->check() ? auth()->user()->email : old('email') }}" />
        </div>

        <div class="alert-custom alert-error" id="errorStep1">
          <i class="bi bi-x-circle-fill"></i>
          <span id="errorStep1Msg">Ingresa un correo válido.</span>
        </div>

        <button class="btn-primary-custom" onclick="goToStep2()">
          Continuar <i class="bi bi-arrow-right"></i>
        </button>

        <div class="footer-link-row">
          <a href="{{ route('login') }}">¿Recordaste tu código? Inicia sesión</a>
        </div>
      </div>

      {{-- ══════ STEP 2: PREGUNTAS ══════ --}}
      <div class="step-panel" id="step2">
        <div class="rec-icon-wrap" style="background:linear-gradient(135deg,var(--accent-4),var(--accent-3))">
          <i class="bi bi-patch-question"></i>
        </div>
        <p class="rec-label">Paso 2 de 3</p>
        <h2 class="rec-title">Preguntas de seguridad</h2>
        <p class="rec-subtitle">Responde las preguntas que configuraste al registrarte.</p>

        <div class="alert-custom alert-error" id="errorStep2">
          <i class="bi bi-x-circle-fill"></i>
          <span id="errorStep2Msg">Una o más respuestas son incorrectas.</span>
        </div>
        <div class="alert-custom alert-info" id="infoStep2">
          <i class="bi bi-info-circle-fill"></i>
          <span id="attemptsMsg"></span>
        </div>

        <div id="questionsContainer">
          {{-- Se cargará dinámicamente --}}
        </div>

        <button class="btn-primary-custom" id="btnVerifyAnswers" onclick="verifyAnswers()">
          Verificar respuestas <i class="bi bi-arrow-right"></i>
        </button>
        <div class="footer-link-row">
          <a href="#" onclick="goBack(); return false;">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>

      {{-- ══════ STEP 3: NUEVO CÓDIGO ══════ --}}
      <div class="step-panel" id="step3">
        <div class="success-checkmark"><i class="bi bi-key-fill"></i></div>
        <p class="rec-label" style="text-align:center">Paso 3 de 3</p>
        <h2 class="success-title">¡Identidad verificada!</h2>
        <p class="success-sub">
          Hemos generado un nuevo código de seguridad.<br>
          Guárdalo en un lugar seguro — lo necesitarás para confirmar tus compras.
        </p>

        <div class="new-code-box">
          <div class="new-code-label">Tu nuevo código de seguridad</div>
          <div class="new-code-value" id="newCodeValue">------</div>
          <p style="color:rgba(255,255,255,0.6); font-size:0.85rem; margin-top:0.5rem;">
    Revisa tu bandeja de entrada
</p>
          <div class="code-warning">
            <i class="bi bi-shield-exclamation me-1"></i> No compartas tu código con nadie
          </div>
        </div>

        <div class="alert-custom alert-success show" style="margin-bottom:1.5rem">
          <i class="bi bi-envelope-check-fill"></i>
          <span>El código ha sido enviado a tu correo electrónico.</span>
        </div>

        <a href="{{ url('/') }}" class="btn-goto-login">
  <i class="bi bi-house"></i> Ir al inicio
</a>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let currentStep = 1;
  let attempts    = 3;

  function updateProgress(step) {
    const pct = [33, 66, 100];
    document.getElementById('progressFill').style.width = pct[step - 1] + '%';
    for (let i = 1; i <= 3; i++) {
      const dot = document.getElementById('dot' + i);
      dot.classList.remove('active', 'done');
      if (i < step) { dot.classList.add('done'); dot.innerHTML = '<i class="bi bi-check" style="font-size:0.7rem"></i>'; }
      else if (i === step) dot.classList.add('active');
    }
    for (let i = 1; i <= 2; i++) {
      document.getElementById('line' + i).classList.toggle('done', i < step);
    }
  }

  function showStep(n) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');
    currentStep = n;
    updateProgress(n);
  }

  function goBack() {
    if (currentStep > 1) showStep(currentStep - 1);
  }

  // STEP 1 — verificar email y buscar preguntas
  function goToStep2() {
    const email    = document.getElementById('emailField').value.trim();
    const errorBox = document.getElementById('errorStep1');
    errorBox.classList.remove('show');

    if (!email || !email.includes('@')) {
      document.getElementById('errorStep1Msg').textContent = 'Ingresa un correo válido.';
      errorBox.classList.add('show');
      return;
    }

    document.getElementById('loadingOverlay').classList.add('show');
    document.getElementById('loadingMsg').textContent = 'Buscando preguntas de seguridad...';

    fetch('{{ route("auth.buscar.preguntas") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ email: email })
    })
    .then(r => r.json())
    .then(data => {
      document.getElementById('loadingOverlay').classList.remove('show');
      if (data.success) {
        // Renderizar las preguntas
        const container = document.getElementById('questionsContainer');
        container.innerHTML = '';
        data.preguntas.forEach((p, index) => {
          const num = index + 1;
          container.innerHTML += `
            <div class="question-card" id="qCard${num}">
              <div class="question-num">Pregunta ${num}</div>
              <div class="question-text">${p.pregunta}</div>
              <input type="text" class="question-input" id="ans${num}" placeholder="Tu respuesta..." autocomplete="off" />
              <div class="question-status" id="qStatus${num}"></div>
            </div>
          `;
        });
        showStep(2);
      } else {
        document.getElementById('errorStep1Msg').textContent = data.message || 'No se encontró el usuario.';
        errorBox.classList.add('show');
      }
    })
    .catch(() => {
      document.getElementById('loadingOverlay').classList.remove('show');
      document.getElementById('errorStep1Msg').textContent = 'Error de conexión.';
      errorBox.classList.add('show');
    });
  }

  // STEP 2 — verificar respuestas
  function verifyAnswers() {
    const a1 = document.getElementById('ans1').value.trim();
    const a2 = document.getElementById('ans2').value.trim();
    const a3 = document.getElementById('ans3').value.trim();
    const errorBox = document.getElementById('errorStep2');
    const infoBox  = document.getElementById('infoStep2');
    errorBox.classList.remove('show');
    infoBox.classList.remove('show');

    if (!a1 || !a2 || !a3) {
      document.getElementById('errorStep2Msg').textContent = 'Por favor responde todas las preguntas.';
      errorBox.classList.add('show');
      return;
    }

    document.getElementById('loadingOverlay').classList.add('show');
    document.getElementById('loadingMsg').textContent = 'Verificando respuestas...';

    fetch('{{ route("auth.verificar.respuestas") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        email: document.getElementById('emailField').value.trim(),
        respuesta_1: a1,
        respuesta_2: a2,
        respuesta_3: a3
      })
    })
    .then(r => r.json())
    .then(data => {
      document.getElementById('loadingOverlay').classList.remove('show');
      if (data.success) {
    document.getElementById('newCodeValue').innerText = '📧 Enviado a tu correo';
    showStep(3);
} else {
        attempts--;
        document.getElementById('errorStep2Msg').textContent = data.message || 'Respuestas incorrectas.';
        errorBox.classList.add('show');
        if (attempts > 0) {
          document.getElementById('attemptsMsg').textContent = `Te quedan ${attempts} intento${attempts > 1 ? 's' : ''}.`;
          infoBox.classList.add('show');
        } else {
          document.getElementById('attemptsMsg').textContent = 'Contacta al administrador.';
          infoBox.classList.add('show');
          document.getElementById('btnVerifyAnswers').disabled = true;
        }
      }
    })
    .catch(() => {
      document.getElementById('loadingOverlay').classList.remove('show');
      document.getElementById('errorStep2Msg').textContent = 'Error de servidor.';
      errorBox.classList.add('show');
    });
  }

  // Revelar código
  let revealed = false;
  function revealCode() {
    revealed = !revealed;
    const codeEl = document.getElementById('newCodeValue');
    const btnEl  = document.getElementById('btnReveal');
    if (revealed) {
      codeEl.classList.add('revealed');
      btnEl.innerHTML = '<i class="bi bi-eye-slash me-1"></i> Ocultar código';
    } else {
      codeEl.classList.remove('revealed');
      btnEl.innerHTML = '<i class="bi bi-eye me-1"></i> Mostrar código';
    }
  }
</script>

@endsection
