<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NuevaCuenta extends Controller
{
    // Muestra el formulario
    public function crear()
    {
        return view('crearcuenta.crear');
    }

    // Procesa el formulario
    public function guardar(Request $request)
    {
        $request->validate([
            // Cobrador
            'nombre_cobrador'    => 'required|string|max:255',
            'documento_cobrador' => 'required|string|max:50',
            'direccion_cobrador' => 'required|string|max:255',
            'telefono_cobrador'  => 'required|string|max:20',
            'email_cobrador'     => 'required|email|max:255',

            // Cliente
            'nombre_cliente'     => 'required|string|max:255',
            'documento_cliente'  => 'required|string|max:50',

            // Cobro
            'concepto'           => 'required|string|max:255',
            'periodo'            => 'required|string|max:100',
            'valor_cobro'        => 'required|numeric|min:0',
            'valor_bruto'        => 'required|numeric|min:0',
            'retencion'          => 'nullable|numeric|min:0',
            'otros_descuentos'   => 'nullable|numeric|min:0',
            'total_pagar'        => 'required|numeric|min:0',

            // Forma de pago
            'forma_pago'         => 'required|string|max:50',
        ]);


        return redirect()->route('dashboard')
                         ->with('success', 'Cuenta de cobro creada con éxito.');
    }
}