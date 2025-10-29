@extends('layouts.app')

@section('title', 'Iniciar Sesión - CuentasCobro')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600&display=swap" rel="stylesheet">

<style>
    body { background: linear-gradient(135deg, #1f3f99ff 0%, #000000ff 100%); height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Dosis','Poppins',sans-serif; }
    .login-box { background: rgba(10, 20, 40, 0.95); padding: 64px 48px 48px 48px; border-radius: 18px; width: 100%; max-width: 520px; box-shadow: 0 0 40px rgba(31,63,153,.25); text-align: center; color: #fff; position: relative; border: 2px solid #1f3f99ff; }
    .login-box .avatar { background: linear-gradient(135deg, #1f3f99ff 0%, #000000ff 100%); width: 100px; height: 100px; border-radius: 50%; position: absolute; top: -50px; left: 50%; transform: translateX(-50%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(31,63,153,.18); }
    .login-box .avatar i { font-size: 50px; color: #fff; }
    .inputbox { position: relative; width: 100%; display: flex; flex-direction: column; }
    .inputbox label { margin-bottom: 8px; color: #45f3ff; font-size: 1.1em; letter-spacing: .05em; text-align: left; }
    .inputbox input { width: 100%; padding: 16px 14px; background: rgba(255,241,241,.05); border-radius: 8px; outline: none; border: none; color: #fff; font-size: 1.1em; letter-spacing: .05em; transition: .4s ease; font-family: 'Dosis','Poppins',sans-serif; position: relative; z-index: 2; }
    .inputbox input:focus { background: linear-gradient(90deg, rgba(255,255,255,.15), rgba(234,84,85,.15)); box-shadow: 0 0 10px rgba(69,243,255,.3); }
    .login-box .form-check-label { color: #b0b0b0; font-size: 1rem; }
    .login-box .forgot-password { float: right; color: #ea5455; font-size: .95rem; text-decoration: none; transition: color .3s; }
    .login-box .forgot-password:hover { color: #fff; }
    .login-box button { transition: all .3s ease-in-out; font-family: "Dosis", sans-serif; width: 180px; height: 58px; border-radius: 50px; background-image: linear-gradient(135deg, #1f3f99ff 0%, #9290a3ff 100%); box-shadow: 0 20px 30px -6px rgba(102,197,214,.5); border: none; font-size: 22px; color: white; display: inline-flex; align-items: center; justify-content: center; margin: 30px auto 0 auto; cursor: pointer; }
    .login-box button:hover { transform: translateY(3px); box-shadow: none; }
    .login-box button:active { opacity: .6; }
    .login-box button:focus { box-shadow: 0 0 0 4px rgba(234,84,85,.2); outline: none; }
</style>

<div class="login-box">
    <div class="avatar"><i class="fas fa-user"></i></div>

    <h3 class="mb-4">Iniciar Sesión</h3>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li style="list-style: none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="inputbox" style="margin-bottom: 28px;">
            <label for="email">Usuario</label>
            <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}">
        </div>

        <div class="inputbox" style="margin-bottom: 28px;">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-check mb-3 text-start">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Recordarme</label>
            <a href="{{ route('register') }}" class="forgot-password">Crear usuario</a>
        </div>

        <button type="submit">Ingresar</button>
    </form>
</div>
@endsection
