@extends('layouts.app')

@section('title', 'Restablecer Contraseña — Museo de Arte Contemporáneo')

@section('content')
<style>
  :root {
    --accent-1: #FF4D6D; --accent-2: #FFBE0B; --accent-3: #3A86FF;
    --accent-4: #8338EC; --dark: #0D0D0D; --light: #F8F5F0;
  }
  .recovery-wrapper {
    min-height: calc(100vh - 65px);
    display: flex; align-items: center; justify-content: center;
    background: var(--dark); padding: 2rem 1rem;
  }
  .recovery-card {
    width: 100%; max-width: 440px;
    background: var(--light); border-radius: 24px; padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  }
  .title { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 900; color: var(--dark); margin-bottom: 1.5rem; }
  .form-label-custom { font-size: 0.78rem; font-weight: 500; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; display: block; }
  .input-wrap { position: relative; margin-bottom: 1.2rem; }
  .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 1rem; }
  .form-control-custom { width: 100%; border: 1.5px solid #ddd; border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2.6rem; font-size: 0.9rem; outline: none; }
  .btn-recovery { width: 100%; background: var(--accent-1); color: #fff; border: none; border-radius: 14px; padding: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
  .btn-recovery:hover { background: #e0003c; transform: translateY(-1px); }
</style>

<div class="recovery-wrapper">
  <div class="recovery-card">
    <h1 class="title">Nueva Contraseña</h1>

    <form method="POST" action="{{ route('password.store') }}">
      @csrf

      <!-- Password Reset Token -->
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <label class="form-label-custom">Correo electrónico</label>
      <div class="input-wrap">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" name="email" class="form-control-custom" value="{{ old('email', $request->email) }}" required autofocus readonly />
      </div>
      @error('email') <div class="text-danger small mt-n2 mb-3">{{ $message }}</div> @enderror

      <label class="form-label-custom">Contraseña</label>
      <div class="input-wrap">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required autocomplete="new-password" />
      </div>
      @error('password') <div class="text-danger small mt-n2 mb-3">{{ $message }}</div> @enderror

      <label class="form-label-custom">Confirmar Contraseña</label>
      <div class="input-wrap">
        <i class="bi bi-lock-check input-icon"></i>
        <input type="password" name="password_confirmation" class="form-control-custom" placeholder="••••••••" required autocomplete="new-password" />
      </div>
      @error('password_confirmation') <div class="text-danger small mt-n2 mb-3">{{ $message }}</div> @enderror

      <button type="submit" class="btn-recovery">Restablecer contraseña</button>
    </form>
  </div>
</div>
@endsection
