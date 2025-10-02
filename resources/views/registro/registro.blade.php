@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Cuenta de Cobro</h2>
    <form action="{{ route('cuenta.cobro.guardar') }}" method="POST">
        @csrf

        {{-- Datos del cobrador --}}
        <h4>Datos de quien cobra</h4>
        <div class="mb-3">
            <label for="nombre_cobrador" class="form-label">Nombre completo</label>
            <input type="text" name="nombre_cobrador" id="nombre_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="documento_cobrador" class="form-label">Número de documento</label>
            <input type="text" name="documento_cobrador" id="documento_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="direccion_cobrador" class="form-label">Dirección</label>
            <input type="text" name="direccion_cobrador" id="direccion_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefono_cobrador" class="form-label">Teléfono</label>
            <input type="text" name="telefono_cobrador" id="telefono_cobrador" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email_cobrador" class="form-label">Correo electrónico</label>
            <input type="email" name="email_cobrador" id="email_cobrador" class="form-control" required>
        </div>

        <hr>

        {{-- Datos del cliente --}}
        <h4>Datos de a quién se cobra</h4>
        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre / Razón social</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="documento_cliente" class="form-label">NIT o número de identificación</label>
            <input type="text" name="documento_cliente" id="documento_cliente" class="form-control" required>
        </div>

        <hr>

        {{-- Datos del cobro --}}
        <h4>Detalles del cobro</h4>
        <div class="mb-3">
            <label for="concepto" class="form-label">Concepto del cobro</label>
            <input type="text" name="concepto" id="concepto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="periodo" class="form-label">Periodo del cobro</label>
            <input type="text" name="periodo" id="periodo" class="form-control" placeholder="Ej: Septiembre 2025" required>
        </div>

        <div class="mb-3">
            <label for="valor_cobro" class="form-label">Valor del cobro</label>
            <input type="number" name="valor_cobro" id="valor_cobro" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="valor_bruto" class="form-label">Valor bruto</label>
            <input type="number" name="valor_bruto" id="valor_bruto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="retencion" class="form-label">Retención en la fuente (si aplica)</label>
            <input type="number" name="retencion" id="retencion" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label for="otros_descuentos" class="form-label">Otros descuentos</label>
            <input type="number" name="otros_descuentos" id="otros_descuentos" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label for="total_pagar" class="form-label">Total a pagar</label>
            <input type="number" name="total_pagar" id="total_pagar" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="forma_pago" class="form-label">Forma de pago</label>
            <select name="forma_pago" id="forma_pago" class="form-control" required>
                <option value="">Seleccione una opción</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia bancaria</option>
                <option value="cheque">Cheque</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Cuenta de Cobro</button>
    </form>
</div>

<script>
    // Cálculo automático del total
    const valorBruto = document.getElementById('valor_bruto');
    const retencion = document.getElementById('retencion');
    const otros = document.getElementById('otros_descuentos');
    const total = document.getElementById('total_pagar');

    function calcularTotal() {
        const bruto = parseFloat(valorBruto.value) || 0;
        const ret = parseFloat(retencion.value) || 0;
        const desc = parseFloat(otros.value) || 0;
        total.value = bruto - ret - desc;
    }

    valorBruto.addEventListener('input', calcularTotal);
    retencion.addEventListener('input', calcularTotal);
    otros.addEventListener('input', calcularTotal);
</script>
@endsection
