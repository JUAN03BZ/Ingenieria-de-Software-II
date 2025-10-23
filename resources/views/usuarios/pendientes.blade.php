<!-- resources/views/admin/usuarios/pendientes.blade.php -->
@extends('layouts.app')

@section('title','Usuarios pendientes')

@section('content')
<div class="container py-4">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <h3 class="mb-3">Usuarios pendientes de aprobación</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nombre</th><th>Email</th><th>Registro</th><th>Aprobación</th><th>Asignar rol</th>
        </tr>
        </thead>
        <tbody>
        @forelse($usuarios as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.usuarios.aprobar', $u->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">Aprobar</button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.usuarios.asignar-rol', $u->id) }}" class="d-flex gap-2">
                        @csrf
                        <select name="role_id" class="form-select form-select-sm" required>
                            <option value="" selected disabled>Seleccione rol</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary">Asignar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hay usuarios pendientes.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $usuarios->links() }}
</div>
@endsection
