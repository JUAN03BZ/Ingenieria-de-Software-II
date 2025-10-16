@extends('layouts.app')

@section('title', 'Registro Exitoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-light border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <h2 class="mb-4 text-success"><i class="fas fa-check-circle me-2"></i>¡Gracias por su registro!</h2>
                    <p class="fs-5">Su cuenta ha sido creada correctamente.</p>
                    <p class="fs-6 text-info">Espere la aprobación de rol por parte del supervisor.</p>
                    <a href="/login" class="btn btn-primary mt-4">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
