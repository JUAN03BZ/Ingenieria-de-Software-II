@extends('layouts.app')

@section('title', 'Usuarios pendientes de aprobación')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuarios-pendientes.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>
                <i class="fas fa-users"></i>
                Usuarios pendientes de aprobación
            </h2>
            <p class="text-muted">Asigna roles a los usuarios registrados</p>
        </div>
    </div>

    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($usuariosPendientes->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-user me-2"></i>Nombre</th>
                                <th><i class="fas fa-envelope me-2"></i>Email</th>
                                <th><i class="fas fa-calendar me-2"></i>Fecha de registro</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuariosPendientes as $usuario)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">#{{ $usuario->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-info me-2"></i>
                                    {{ $usuario->name }}
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.usuarios.asignar-rol', $usuario->id) }}" 
                                          method="POST" 
                                          class="d-flex gap-2">
                                        @csrf
                                        <select name="role_id" class="form-select form-select-sm" required>
                                            <option value="">Seleccionar rol...</option>
                                            @foreach($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-check me-1"></i>Asignar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <h4>No hay usuarios pendientes</h4>
                <p>Todos los usuarios tienen roles asignados</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-cerrar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush