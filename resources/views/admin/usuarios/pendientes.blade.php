@extends('layouts.app')

@section('title', 'Usuarios pendientes de aprobación')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="text-info"><i class="fas fa-users me-2"></i>Usuarios pendientes de aprobación</h2>
            <p class="text-muted">Asigna roles a los usuarios registrados</p>
        </div>
    </div>

    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($usuariosPendientes->count() > 0)
        <div class="card bg-dark border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha de registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuariosPendientes as $usuario)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $usuario->id }}</span></td>
                                <td>
                                    <i class="fas fa-user text-info me-2"></i>
                                    {{ $usuario->name }}
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.usuarios.asignar-rol', $usuario->id) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <select name="role_id" class="form-select form-select-sm bg-dark text-light" required>
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
        <div class="card bg-dark border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                <h4 class="text-light">No hay usuarios pendientes</h4>
                <p class="text-muted">Todos los usuarios tienen roles asignados</p>
            </div>
        </div>
    @endif
</div>
@endsection