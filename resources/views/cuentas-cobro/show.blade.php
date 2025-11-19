@extends('layouts.app')

@section('title', 'Detalle Cuenta de Cobro')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detalle-cuenta.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>
        <i class="fas fa-file-invoice me-2"></i>
        Detalle Cuenta de Cobro #{{ $cuenta->id }}
    </h2>

    <ul class="list-group mb-4">
        <li class="list-group-item">
            <strong><i class="fas fa-user me-1"></i>Cobrador:</strong> 
            {{ $cuenta->nombre_cobrador }}
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-id-card me-1"></i>Documento cobrador:</strong> 
            {{ $cuenta->documento_cobrador }}
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-building me-1"></i>Cliente:</strong> 
            {{ $cuenta->nombre_cliente }}
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-dollar-sign me-1"></i>Monto:</strong> 
            ${{ number_format($cuenta->monto, 2, ',', '.') }}
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-info-circle me-1"></i>Estado:</strong>
            @php
                $estadoConfig = [
                    'pendiente' => ['class' => 'warning', 'icon' => 'clock'],
                    'aprobada'  => ['class' => 'primary', 'icon' => 'check-circle'],
                    'pagada'    => ['class' => 'success', 'icon' => 'money-bill-wave'],
                    'rechazada' => ['class' => 'danger', 'icon' => 'times-circle'],
                    'revision'  => ['class' => 'info', 'icon' => 'search'],
                ];
                $config = $estadoConfig[$cuenta->estado] ?? ['class' => 'secondary', 'icon' => 'question'];
            @endphp
            <span class="badge bg-{{ $config['class'] }}">
                <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                {{ ucfirst($cuenta->estado) }}
            </span>
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-tasks me-1"></i>Fase actual:</strong> 
            <span class="badge bg-info">{{ ucfirst($cuenta->fase) }}</span>
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-align-left me-1"></i>Descripción:</strong> 
            {{ $cuenta->descripcion }}
        </li>
        <li class="list-group-item">
            <strong><i class="fas fa-calendar me-1"></i>Fecha emisión:</strong> 
            {{ optional($cuenta->fecha_emision)->format('d/m/Y') ?? $cuenta->fecha_emision }}
        </li>
        @if($cuenta->observaciones)
            <li class="list-group-item">
                <strong><i class="fas fa-comment me-1"></i>Observaciones:</strong> 
                {{ $cuenta->observaciones }}
            </li>
        @endif
    </ul>

    <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('cuenta.cobro.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
        
        @can('update', $cuenta)
            <a href="{{ route('cuenta.cobro.edit', $cuenta->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Editar
            </a>
        @endcan
        
        @can('delete', $cuenta)
            <form action="{{ route('cuenta.cobro.destroy', $cuenta->id) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('¿Eliminar esta cuenta de cobro?');">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
            </form>
        @endcan

        {{-- BOTÓN: Descargar/exportar PDF --}}
        @auth
            <form action="{{ route('cuenta.cobro.exportar.pdf', $cuenta) }}" 
                  method="POST" 
                  class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-outline-dark">
                    <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                </button>
            </form>
        @endauth
    </div>

    {{-- HISTORIAL DE FLUJO DE REVISIÓN --}}
    @if($cuenta->flujos && $cuenta->flujos->count())
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-stream me-2"></i>Historial de flujo
        </div>
        <ul class="list-group list-group-flush">
            @foreach($cuenta->flujos as $f)
                <li class="list-group-item">
                    <div>
                        <strong>{{ ucfirst($f->rol) }}</strong> — 
                        <b>{{ ucfirst($f->accion) }}</b>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $f->created_at->format('d/m/Y H:i') }}
                        @if($f->user) 
                            <i class="fas fa-user me-1 ms-2"></i>
                            por {{ $f->user->name }} 
                        @endif
                    </small>
                    @if($f->comentario)
                        <em><i class="fas fa-comment me-1"></i>{{ $f->comentario }}</em>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- BLOQUES DE FORMULARIO ÚNICOS POR FASE/ROL --}}
    @auth
      {{-- Contratista: Enviar a Supervisor --}}
      @if(auth()->user()->role->name === 'contratista' && $cuenta->fase === 'creada')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-paper-plane me-2"></i>Acción disponible
            </div>
            <div class="card-body">
                <form action="{{ route('cuenta.cobro.enviar.supervisor', $cuenta) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Enviar a Supervisor
                    </button>
                </form>
            </div>
        </div>
      @endif

      @php
        $idSupervisor = \App\Models\Roles::where('name', 'supervisor')->value('id');
      @endphp

      {{-- Supervisor: Aprobar/Rechazar --}}
      @if((auth()->user()->role->name === 'supervisor' || auth()->user()->role_id == $idSupervisor) && $cuenta->fase === 'supervisor')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-check-double me-2"></i>Decisión de Supervisor
            </div>
            <div class="card-body">
                <form action="{{ route('cuenta.cobro.supervisor', $cuenta) }}" 
                      method="POST" 
                      class="row g-2 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <select name="decision" class="form-select" required>
                            <option value="aprobado">Aprobar</option>
                            <option value="rechazado">Rechazar</option>
                        </select>
                    </div>
                    <div class="col">
                        <input name="comentario" 
                               class="form-control" 
                               placeholder="Motivo (obligatorio)" 
                               required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
      @endif

      {{-- Contratación: Aprobar/Rechazar --}}
      @if(auth()->user()->role->name === 'contratacion' && $cuenta->fase === 'contratacion')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-handshake me-2"></i>Decisión de Contratación
            </div>
            <div class="card-body">
                <form action="{{ route('cuenta.cobro.contratacion', $cuenta) }}" 
                      method="POST" 
                      class="row g-2 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <select name="decision" class="form-select" required>
                            <option value="aprobado">Aprobar</option>
                            <option value="rechazado">Rechazar</option>
                        </select>
                    </div>
                    <div class="col">
                        <input name="comentario" 
                               class="form-control" 
                               placeholder="Motivo (obligatorio)" 
                               required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
      @endif

      {{-- Tesorería: Aprobar/Rechazar con Fondos --}}
      @if(auth()->user()->role->name === 'tesoreria' && $cuenta->fase === 'tesoreria')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-coins me-2"></i>Decisión de Tesorería
            </div>
            <div class="card-body">
                <form action="{{ route('cuenta.cobro.tesoreria', $cuenta) }}" 
                      method="POST" 
                      class="row g-2 align-items-center">
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
                        <input name="comentario" 
                               class="form-control" 
                               placeholder="Motivo (obligatorio)" 
                               required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
      @endif

      {{-- Ordenador de Gasto: Decisión Final --}}
      @if(auth()->user()->role->name === 'ordenador_gasto' && $cuenta->fase === 'ordenador')
        <div class="card">
            <div class="card-header">
                <i class="fas fa-gavel me-2"></i>Decisión Final - Ordenador de Gasto
            </div>
            <div class="card-body">
                <form action="{{ route('cuenta.cobro.ordenador', $cuenta) }}" 
                      method="POST" 
                      class="row g-2 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <select name="decision" class="form-select" required>
                            <option value="aprobado">Aprobar (final)</option>
                            <option value="rechazado">Rechazar</option>
                        </select>
                    </div>
                    <div class="col">
                        <input name="comentario" 
                               class="form-control" 
                               placeholder="Motivo (obligatorio)" 
                               required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success">
                            <i class="fas fa-check-double me-1"></i>Registrar decisión
                        </button>
                    </div>
                </form>
            </div>
        </div>
      @endif
    @endauth
</div>
@endsection