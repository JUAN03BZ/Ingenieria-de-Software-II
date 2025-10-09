@extends('layouts.app')

@section('title', 'Iniciar Sesión - CuentasCobro')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #0d47a1, #1b5e20);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
    }

    .login-box {
        background: rgba(0, 0, 0, 0.3);
        padding: 40px 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 380px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.4);
        text-align: center;
        color: #fff;
        position: relative;
    }

    .login-box .avatar {
        background: #19b961ff;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        position: absolute;
        top: -45px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-box .avatar i {
        font-size: 45px;
        color: #fff;
    }

    .login-box input.form-control {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-bottom: 2px solid #4caf50;
        border-radius: 0;
        color: #fff;
        margin-bottom: 20px;
    }

    .login-box input.form-control::placeholder {
        color: #ccc;
    }

    .login-box input:focus {
        box-shadow: none;
        border-color: #81c784;
    }

    .login-box .form-check-label {
        color: #ccc;
        font-size: 0.9rem;
    }

    .login-box .forgot-password {
        float: right;
        color: #81c784;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .login-box button {
        background-color: #2e7d32;
        border: none;
        width: 100%;
        padding: 10px;
        color: #fff;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }

    .login-box button:hover {
        background-color: #388e3c;
    }
</style>

<div class="login-box">
    <div class="avatar">
        <i class="fas fa-user"></i>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mt-5">
            <div class="mb-3 text-start">
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email"
                       placeholder="Username" 
                       value="{{ old('email') }}"
                       required autofocus>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 text-start">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="**********"
                       required>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recuerdame</label>
                </div>
                <a href="#" class="forgot-password">Crear Cuenta</a>
            </div>

            <button type="submit">Ingresar</button>
        </div>
    </form>
</div>
@endsection
