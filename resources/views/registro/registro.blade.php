<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - CuentasCobro</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>
<body>
    <div class="register-container">
        <div class="modal-container open">
            <div class="modal-left">
                <h1 class="modal-title">Crear Cuenta</h1>
                <p class="modal-desc">Únete a CuentasCobro y comienza a gestionar tus cuentas</p>

                {{-- Mensajes flash --}}
                @if(session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert error">{{ session('error') }}</div>
                @endif

                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="alert error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-block">
                        <label for="name" class="input-label">Nombre completo</label>
                        <input type="text" name="name" id="name" placeholder="Ej: Juan Pérez" required autofocus value="{{ old('name') }}">
                    </div>

                    <div class="input-block">
                        <label for="email" class="input-label">Correo electrónico</label>
                        <input type="email" name="email" id="email" placeholder="usuario@ejemplo.com" required value="{{ old('email') }}">
                    </div>

                    <div class="input-block">
                        <label for="password" class="input-label">Contraseña</label>
                        <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres" required minlength="8">
                    </div>

                    <div class="input-block">
                        <label for="password_confirmation" class="input-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite tu contraseña" required minlength="8">
                    </div>

                    <div class="modal-buttons">
                        <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                        <button type="submit" class="input-button">Registrarme</button>
                    </div>
                </form>
            </div>

            <div class="modal-right"> <img src="{{ asset('img/img1.jpg') }}" alt="">

               
            </div>
        </div>
    </div>
</body>
</html>