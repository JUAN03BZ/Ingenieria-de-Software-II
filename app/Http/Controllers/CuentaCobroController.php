<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaCobroController extends Controller
{
    public function create()
    {
        // Muestra el formulario de creación
        return view('crearcuenta.crear'); 
    }

    public function store(Request $request)
    {
        // Validación (como te di antes)
        $validated = $request->validate([
            'nombre_cobrador' => 'required|string|max:255',
            'documento_cobrador' => 'required|string|max:20|unique:cuentas_cobro,documento_cobrador',
            'direccion_cobrador' => 'required|string|max:500',
            'telefono_cobrador' => 'required|string|max:20',
            'email_cobrador' => 'required|email|unique:cuentas_cobro,email_cobrador',
            'nombre_cliente' => 'required|string|max:255',
            'documento_cliente' => 'required|string|max:20|unique:cuentas_cobro,documento_cliente',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'fecha_emision' => 'nullable|date',
        ]);

        // Agregar user_id del usuario logueado
        $validated['user_id'] = Auth::id();
        $validated['estado'] = 'pendiente'; // Por defecto

        // Guardar en BD
        $cuenta = CuentaCobro::create($validated);

        return redirect()->route('dashboard')->with('success', 'Cuenta de cobro creada exitosamente con ID: ' . $cuenta->id);
    }

    public function index()
    {
        // Lista todas las cuentas del usuario logueado (o todas si eres admin)
        $cuentas = CuentaCobro::where('user_id', Auth::id())->get(); 
        return view('cuenta-cobro.index', compact('cuentas')); 
    }
}