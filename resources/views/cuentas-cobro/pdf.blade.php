<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Cobro #{{ $cuenta->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            padding: 30px;
            background: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .cuenta-id {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section-title {
            background: #ecf0f1;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            font-weight: bold;
            color: #2c3e50;
            border-left: 4px solid #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table tr {
            border-bottom: 1px solid #ecf0f1;
        }
        table th {
            text-align: left;
            padding: 12px 10px;
            font-weight: bold;
            color: #2c3e50;
            width: 35%;
            background: #f8f9fa;
        }
        table td {
            padding: 12px 10px;
            color: #555;
        }
        .estado {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
        }
        .estado.pendiente { background: #fff3cd; color: #856404; }
        .estado.aprobada { background: #d1ecf1; color: #0c5460; }
        .estado.pagada { background: #d4edda; color: #155724; }
        .estado.rechazada { background: #f8d7da; color: #721c24; }
        .estado.revision { background: #d1ecf1; color: #0c5460; }
        .monto {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }
        .historial {
            margin-top: 20px;
        }
        .historial-item {
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .historial-item strong {
            color: #2c3e50;
        }
        .historial-item .fecha {
            color: #7f8c8d;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }
        .historial-item .comentario {
            color: #555;
            font-style: italic;
            margin-top: 8px;
            display: block;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 12px;
        }
        .no-data {
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Encabezado --}}
        <div class="header">
            <h1>CUENTA DE COBRO</h1>
            <p>Sistema de Gestión de Cuentas</p>
            <span class="cuenta-id">#{{ $cuenta->id }}</span>
        </div>

        {{-- Información General --}}
        <div class="section-title">INFORMACIÓN GENERAL</div>
        <table>
            <tr>
                <th>Cobrador:</th>
                <td>{{ $cuenta->nombre_cobrador }}</td>
            </tr>
            <tr>
                <th>Documento cobrador:</th>
                <td>{{ $cuenta->documento_cobrador }}</td>
            </tr>
            <tr>
                <th>Dirección:</th>
                <td>{{ $cuenta->direccion_cobrador }}</td>
            </tr>
            <tr>
                <th>Teléfono:</th>
                <td>{{ $cuenta->telefono_cobrador }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $cuenta->email_cobrador }}</td>
            </tr>
        </table>

        {{-- Información del Cliente --}}
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <table>
            <tr>
                <th>Cliente:</th>
                <td>{{ $cuenta->nombre_cliente }}</td>
            </tr>
            <tr>
                <th>Documento cliente:</th>
                <td>{{ $cuenta->documento_cliente }}</td>
            </tr>
        </table>

        {{-- Detalles de la Cuenta --}}
        <div class="section-title">DETALLES DE LA CUENTA</div>
        <table>
            <tr>
                <th>Monto:</th>
                <td><span class="monto">${{ number_format($cuenta->monto, 2) }}</span></td>
            </tr>
            <tr>
                <th>Estado:</th>
                <td>
                    @php
                        $estadoClass = match($cuenta->estado) {
                            'pendiente' => 'pendiente',
                            'aprobada'  => 'aprobada',
                            'pagada'    => 'pagada',
                            'rechazada' => 'rechazada',
                            'revision'  => 'revision',
                            default     => 'pendiente'
                        };
                    @endphp
                    <span class="estado {{ $estadoClass }}">{{ strtoupper($cuenta->estado) }}</span>
                </td>
            </tr>
            <tr>
                <th>Fase actual:</th>
                <td>{{ ucfirst($cuenta->fase) }}</td>
            </tr>
            <tr>
                <th>Fecha emisión:</th>
                <td>{{ optional($cuenta->fecha_emision)->format('d/m/Y') ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <th>Descripción:</th>
                <td>{{ $cuenta->descripcion ?: 'Sin descripción' }}</td>
            </tr>
        </table>

        {{-- Historial de Flujo --}}
        @if($cuenta->flujos && $cuenta->flujos->count())
        <div class="section-title">HISTORIAL DE REVISIÓN Y APROBACIÓN</div>
        <div class="historial">
            @foreach($cuenta->flujos as $f)
                <div class="historial-item">
                    <strong>{{ strtoupper($f->rol) }}</strong> — 
                    <strong style="color: {{ $f->accion === 'aprobado' ? '#27ae60' : ($f->accion === 'rechazado' ? '#e74c3c' : '#3498db') }}">
                        {{ strtoupper($f->accion) }}
                    </strong>
                    <span class="fecha">
                        📅 {{ $f->created_at->format('d/m/Y H:i') }}
                        @if($f->user) | 👤 {{ $f->user->name }} @endif
                    </span>
                    @if($f->comentario)
                        <span class="comentario">💬 {{ $f->comentario }}</span>
                    @endif
                </div>
            @endforeach
        </div>
        @else
        <div class="section-title">HISTORIAL DE REVISIÓN Y APROBACIÓN</div>
        <p class="no-data">No hay historial de flujo registrado.</p>
        @endif

        {{-- Pie de página --}}
        <div class="footer">
            <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
            <p>Sistema de Gestión de Cuentas de Cobro</p>
        </div>
    </div>
</body>
</html>
