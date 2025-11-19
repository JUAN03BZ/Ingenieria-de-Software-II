@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle Cuenta de Cobro #{{ $cuenta->id }}</h2>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Cobrador:</strong> {{ $cuenta->nombre_cobrador }}</li>
        <li class="list-group-item"><strong>Documento cobrador:</strong> {{ $cuenta->documento_cobrador }}</li>
        <li class="list-group-item"><strong>Cliente:</strong> {{ $cuenta->nombre_cliente }}</li>
        <li class="list-group-item"><strong>Monto:</strong> ${{ number_format($cuenta->monto, 2) }}</li>
        <li class="list-group-item">
            <strong>Estado:</strong>
            @php
                $estadoClass = match($cuenta->estado) {
                    'pendiente' => 'warning',
                    'aprobada'  => 'primary',
                    'pagada'    => 'success',
                    'rechazada' => 'danger',
                    'revision'  => 'info',
                    default     => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $estadoClass }}">{{ ucfirst($cuenta->estado) }}</span>
        </li>
        <li class="list-group-item"><strong>Fase actual:</strong> {{ ucfirst($cuenta->fase) }}</li>
        <li class="list-group-item"><strong>Descripción:</strong> {{ $cuenta->descripcion }}</li>
        <li class="list-group-item"><strong>Fecha emisión:</strong> {{ optional($cuenta->fecha_emision)->format('Y-m-d') ?? $cuenta->fecha_emision }}</li>
        @if($cuenta->observaciones)
            <li class="list-group-item"><strong>Observaciones:</strong> {{ $cuenta->observaciones }}</li>
        @endif
    </ul>

    <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-secondary">Volver al listado</a>
        @can('update', $cuenta)
            <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" class="btn btn-warning">Editar</a>
        @endcan
        @can('delete', $cuenta)
            <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('¿Eliminar esta cuenta de cobro?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
        @endcan

        {{-- BOTÓN: Descargar/exportar PDF visible para cualquier autenticado --}}
        @auth
            <form action="{{ route('cuenta.cobro.exportar.pdf', $cuenta) }}" method="POST" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-outline-dark">
                    <i class="fas fa-file-pdf"></i> Exportar e Imprimir PDF
                </button>
            </form>
        @endauth
    </div>

    {{-- HISTORIAL DE FLUJO DE REVISIÓN --}}
    @if($cuenta->flujos && $cuenta->flujos->count())
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-stream me-2"></i>Historial de flujo</div>
        <ul class="list-group list-group-flush">
            @foreach($cuenta->flujos as $f)
                <li class="list-group-item">
                    <strong>{{ ucfirst($f->rol) }}</strong> — <b>{{ ucfirst($f->accion) }}</b>
                    <small class="text-muted">{{ $f->created_at->format('d/m/Y H:i') }}
                        @if($f->user) por {{ $f->user->name }} @endif
                    </small><br>
                    @if($f->comentario)<em>{{ $f->comentario }}</em>@endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- BLOQUES DE FORMULARIO ÚNICOS POR FASE/ROL --}}
    @auth
      {{-- Mostrar a cualquier contratista si la cuenta está en 'creada' --}}
      @if(auth()->user()->role->name === 'contratista' && $cuenta->fase === 'creada')
        <form action="{{ route('cuenta.cobro.enviar.supervisor', $cuenta) }}" method="POST" class="mt-3">
          @csrf
          <button class="btn btn-primary">Enviar a Supervisor</button>
        </form>
      @endif

      @php
        $idSupervisor = \App\Models\Roles::where('name', 'supervisor')->value('id');
      @endphp

      @if((auth()->user()->role->name === 'supervisor' || auth()->user()->role_id == $idSupervisor) && $cuenta->fase === 'supervisor')
        <form action="{{ route('cuenta.cobro.supervisor', $cuenta) }}" method="POST" class="mt-3 row g-2 align-items-center">
          @csrf
          <div class="col-auto">
            <select name="decision" class="form-select" required>
              <option value="aprobado">Aprobar</option>
              <option value="rechazado">Rechazar</option>
            </select>
          </div>
          <div class="col">
            <input name="comentario" class="form-control" placeholder="Motivo (obligatorio)" required>
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Registrar</button>
          </div>
        </form>
      @endif

      @if(auth()->user()->role->name === 'contratacion' && $cuenta->fase === 'contratacion')
        <form action="{{ route('cuenta.cobro.contratacion', $cuenta) }}" method="POST" class="mt-3 row g-2 align-items-center">
          @csrf
          <div class="col-auto">
            <select name="decision" class="form-select" required>
              <option value="aprobado">Aprobar</option>
              <option value="rechazado">Rechazar</option>
            </select>
          </div>
          <div class="col">
            <input name="comentario" class="form-control" placeholder="Motivo (obligatorio)" required>
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Registrar</button>
          </div>
        </form>
      @endif

      @if(auth()->user()->role->name === 'tesoreria' && $cuenta->fase === 'tesoreria')
        <form action="{{ route('cuenta.cobro.tesoreria', $cuenta) }}" method="POST" class="mt-3 row g-2 align-items-center">
          @csrf
          <div class="col-auto">
            <select name="decision" class="form-select" required>
              <option value="aprobado">Aprobar</option>
              <option value="rechazado">Rechazar</option>
            </select>
          </div>
          <div class="col-auto">
            <select name="hay_fondos" class="form-select" required>
              <option value="1">Con fondos</option>
              <option value="0">Sin fondos</option>
            </select>
          </div>
          <div class="col">
            <input name="comentario" class="form-control" placeholder="Motivo (obligatorio)" required>
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Registrar</button>
          </div>
        </form>
      @endif

      @if(auth()->user()->role->name === 'ordenador_gasto' && $cuenta->fase === 'ordenador')
        <form action="{{ route('cuenta.cobro.ordenador', $cuenta) }}" method="POST" class="mt-3 row g-2 align-items-center">
          @csrf
          <div class="col-auto">
            <select name="decision" class="form-select" required>
              <option value="aprobado">Aprobar (final)</option>
              <option value="rechazado">Rechazar</option>
            </select>
          </div>
          <div class="col">
            <input name="comentario" class="form-control" placeholder="Motivo (obligatorio)" required>
          </div>
          <div class="col-auto">
            <button class="btn btn-success">Registrar decisión</button>
          </div>
        </form>
      @endif
    @endauth
</div>
@endsection
