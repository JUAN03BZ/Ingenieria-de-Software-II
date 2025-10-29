@extends('layouts.app')

@section('title', 'Crear Cuenta - CuentasCobro')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600&display=swap" rel="stylesheet">

<style>
    body { background: linear-gradient(135deg, #1f3f99ff 0%, #000000ff 100%); height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Dosis','Poppins',sans-serif; }
    .register-box { background: rgba(10, 20, 40, 0.95); padding: 48px; border-radius: 18px; width: 100%; max-width: 560px; box-shadow: 0 0 40px rgba(31,63,153,.25); color: #fff; border: 2px solid #1f3f99ff; }
    .register-box h3 { text-align: center; margin-bottom: 18px; }
    .inputbox { margin-bottom: 18px; }
    .inputbox label { display: block; margin-bottom: 6px; color: #45f3ff; }
    .inputbox input { width: 100%; padding: 12px 14px; background: rgba(255,255,255,.08); border: none; border-radius: 8px; color: #fff; }
    .btn-primary { background-image: linear-gradient(135deg, #1f3f99ff 0%, #9290a3ff 100%); border: none; }
    a.link { color: #ea5455; text-decoration: none; }
    a.link:hover { color: #fff; }
</style>

<div class="register-box">
    <h3>Crear Cuenta</h3>

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
                    <li style="list-style:none;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="inputbox">
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="inputbox">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="inputbox">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>

        <div class="inputbox">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a class="link" href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            <button type="submit" class="btn btn-primary px-4">Registrarme</button>
        </div>
    </form>
</div>
@endsection
