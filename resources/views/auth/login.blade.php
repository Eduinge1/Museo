@extends('layouts.app')

@section('title', 'Iniciar Sesión — Museo de Arte Contemporáneo')

@section('content')

<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --mid: #1A1A2E; --light: #F8F5F0;
  }
  * { box-sizing: border-box; }

  /* NAVBAR */
  .navbar-mac { background: var(--dark); padding: 0.85rem 2rem; border-bottom: 2px solid var(--accent-1); }
  .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.5px; text-decoration: none; }
  .navbar-brand-custom span { color: var(--accent-2); }
  .btn-nav-back { background: transparent; color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 0.4rem 1.2rem; font-size: 0.875rem; text-decoration: none; transition: all 0.2s; }
  .btn-nav-back:hover { border-color: var(--accent-2); color: var(--accent-2); }

  /* WRAPPER — centrado */
  .login-wrapper {
    min-height: calc(100vh - 65px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--dark);
    padding: 2rem 1rem;
  }

  /* CARD */
  .login-card {
    width: 100%;
    max-width: 440px;
    background: var(--light);
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    padding: 2.5rem;
  }

  .login-title-label { font-size: 0.7rem; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; color: var(--accent-1); margin-bottom: 0.5rem; }
  .login-title { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: var(--dark); line-height: 1.1; margin-bottom: 0.5rem; }
  .login-subtitle { font-size: 0.875rem; color: #888; margin-bottom: 2rem; }

  /* ROLE TOGGLE */
  .role-toggle { display: flex; background: #e8e4df; border-radius: 12px; padding: 4px; margin-bottom: 1.5rem; gap: 4px; }
  .role-btn { flex: 1; border: none; background: transparent; border-radius: 9px; padding: 0.55rem 0.5rem; font-size: 0.8rem; font-weight: 500; color: #888; cursor: pointer; transition: all 0.25s; display: flex; align-items: center; justify-content: center; gap: 0.4rem; font-family: 'DM Sans', sans-serif; }
  .role-btn.active { background: var(--dark); color: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.18); }

  /* ADMIN BOX */
  .admin-info-box { background: rgba(131,56,236,0.08); border: 1px solid rgba(131,56,236,0.2); border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.8rem; color: var(--accent-4); margin-bottom: 1.2rem; display: none; align-items: center; gap: 0.5rem; }
  .admin-info-box.show { display: flex; }

  /* ALERTS */
  .alert-error   { background: rgba(255,77,109,0.08); border: 1px solid rgba(255,77,109,0.25); border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.82rem; color: var(--accent-1); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem; }
  .alert-success { background: rgba(6,214,160,0.08); border: 1px solid rgba(6,214,160,0.25); border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.82rem; color: #019975; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem; }

  /* FORM */
  .form-label-custom { font-size: 0.78rem; font-weight: 500; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; display: block; }
  .input-wrap { position: relative; margin-bottom: 1.2rem; }
  .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 1rem; pointer-events: none; }
  .form-control-custom { width: 100%; border: 1.5px solid #ddd; border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2.6rem; font-size: 0.9rem; font-family: 'DM Sans', sans-serif; background: #fff; color: var(--dark); transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
  .form-control-custom:focus { border-color: var(--accent-3); box-shadow: 0 0 0 3px rgba(58,134,255,0.12); }
  .form-control-custom::placeholder { color: #bbb; }
  .toggle-pass { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #aaa; cursor: pointer; font-size: 1rem; padding: 0; }
  .toggle-pass:hover { color: var(--dark); }

  .forgot-link { display: block; text-align: right; font-size: 0.8rem; color: var(--accent-3); text-decoration: none; margin-top: -0.5rem; margin-bottom: 1.2rem; }
  .forgot-link:hover { text-decoration: underline; }

  .remember-row { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #888; margin-bottom: 1.5rem; }
  .remember-row input { accent-color: var(--accent-1); width: 15px; height: 15px; }

  .btn-login { width: 100%; background: var(--accent-1); color: #fff; border: none; border-radius: 14px; padding: 0.9rem; font-size: 1rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background 0.2s, transform 0.15s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-login:hover { background: #e0003c; transform: translateY(-1px); }
  .btn-login.admin-mode { background: var(--accent-4); }
  .btn-login.admin-mode:hover { background: #6a22d0; }

  .divider-text { text-align: center; font-size: 0.78rem; color: #bbb; margin: 1.5rem 0 0.75rem; }
  .register-link-row { text-align: center; font-size: 0.82rem; color: #888; }
  .register-link-row a { color: var(--accent-3); text-decoration: none; font-weight: 500; }
  .register-link-row a:hover { text-decoration: underline; }

  /* LOADING */
  .loading-overlay { display: none; position: fixed; inset: 0; background: rgba(13,13,13,0.85); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem; color: #fff; font-size: 0.9rem; }
  .loading-overlay.show { display: flex; }
  .spinner-ring { width: 48px; height: 48px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.1); border-top-color: var(--accent-1); animation: spin 0.8s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>

{{-- NAVBAR --}}
<nav class="navbar-mac d-flex justify-content-between align-items-center">
  <a href="{{ route('home') }}" class="navbar-brand-custom">MAC <span>·</span> Arte</a>
  <a href="{{ route('home') }}" class="btn-nav-back"><i class="bi bi-arrow-left me-1"></i> Volver al catálogo</a>
</nav>

{{-- LOADING --}}
<div class="loading-overlay" id="loadingOverlay">
  <div class="spinner-ring"></div>
  <p>Verificando credenciales...</p>
</div>

<div class="login-wrapper">
  <div class="login-card">

    <p class="login-title-label">Acceso al museo</p>
    <h1 class="login-title">Iniciar sesión</h1>
    <p class="login-subtitle">Ingresa tus credenciales para continuar.</p>

    {{-- Mensajes --}}
    @if(session('success'))
      <div class="alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    {{-- Role toggle --}}
    <div class="role-toggle">
      <button type="button" class="role-btn active" id="btnComprador" onclick="setRole('comprador')">
        <i class="bi bi-person"></i> Comprador
      </button>
      <button type="button" class="role-btn" id="btnAdmin" onclick="setRole('admin')">
        <i class="bi bi-shield-lock"></i> Administrador
      </button>
    </div>

    <div class="admin-info-box" id="adminInfoBox">
      <i class="bi bi-info-circle-fill"></i> Acceso restringido — solo personal autorizado del museo.
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <label class="form-label-custom">Correo electrónico</label>
      <div class="input-wrap">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" name="email" id="emailInput" class="form-control-custom"
               placeholder="tu@correo.com" value="{{ old('email') }}" required />
      </div>

      <label class="form-label-custom">Contraseña</label>
      <div class="input-wrap">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" name="password" id="passwordInput"
               class="form-control-custom" placeholder="••••••••" required />
        <button type="button" class="toggle-pass" onclick="togglePassword()">
          <i class="bi bi-eye" id="eyeIcon"></i>
        </button>
      </div>

      <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('password.request') }}" class="forgot-link m-0">¿Olvidaste tu contraseña?</a>
        <a href="{{ route('auth.recuperacion') }}" class="forgot-link m-0">¿Olvidaste tu código?</a>
      </div>

      <div class="remember-row">
        <input type="checkbox" name="remember" id="rememberMe" />
        <label for="rememberMe">Recordarme en este dispositivo</label>
      </div>

      <button type="submit" class="btn-login" id="loginBtn" onclick="showLoading()">
        Ingresar al museo <i class="bi bi-arrow-right"></i>
      </button>
    </form>

    <div class="divider-text">¿Nuevo en el museo?</div>
    <div class="register-link-row">
      ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí — solo $10</a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function setRole(role) {
    const btnC = document.getElementById('btnComprador');
    const btnA = document.getElementById('btnAdmin');
    const adminBox = document.getElementById('adminInfoBox');
    const loginBtn = document.getElementById('loginBtn');
    if (role === 'admin') {
      btnC.classList.remove('active'); btnA.classList.add('active');
      adminBox.classList.add('show');
      loginBtn.classList.add('admin-mode');
      loginBtn.innerHTML = 'Acceder al panel <i class="bi bi-arrow-right"></i>';
      document.getElementById('emailInput').placeholder = 'admin@museo.com';
    } else {
      btnA.classList.remove('active'); btnC.classList.add('active');
      adminBox.classList.remove('show');
      loginBtn.classList.remove('admin-mode');
      loginBtn.innerHTML = 'Ingresar al museo <i class="bi bi-arrow-right"></i>';
      document.getElementById('emailInput').placeholder = 'tu@correo.com';
    }
  }

  function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }

  function showLoading() {
    const email = document.getElementById('emailInput').value;
    const pwd   = document.getElementById('passwordInput').value;
    if (email && pwd) document.getElementById('loadingOverlay').classList.add('show');
  }
</script>

@endsection
