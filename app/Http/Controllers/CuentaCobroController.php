<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaCobroController extends Controller
{
    public function create()
    {
        if (!Auth::user()->hasRole('alcalde') && !Auth::user()->hasRole('contratista')) {
            abort(403);
        }
        return view('cuentas-cobro.crear');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_cobrador'    => 'required|string|max:255',
            'documento_cobrador' => 'required|string|max:20|unique:cuentas_cobro,documento_cobrador',
            'direccion_cobrador' => 'required|string|max:500',
            'telefono_cobrador'  => 'required|string|max:20',
            'email_cobrador'     => 'required|email|unique:cuentas_cobro,email_cobrador',
            'nombre_cliente'     => 'required|string|max:255',
            'documento_cliente'  => 'required|string|max:20|unique:cuentas_cobro,documento_cliente',
            'monto'              => 'required|numeric|min:0',
            'descripcion'        => 'nullable|string',
            'fecha_emision'      => 'nullable|date',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['estado'] = 'pendiente';
        CuentaCobro::create($validated);
        return redirect()->route('cuenta.cobro.pendientes')->with('success', 'Cuenta de cobro creada exitosamente.');
    }

    public function index()
    {
        $cuentas = CuentaCobro::orderBy('created_at', 'desc')->paginate(10);
        return view('cuentas-cobro.index', compact('cuentas'));
    }

    public function pendientes()
    {
        $cuentas = CuentaCobro::whereIn('estado', ['pendiente', 'revision'])
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('cuentas-cobro.pendientes', compact('cuentas'));
    }

    public function cambiarEstado(Request $request, CuentaCobro $cuenta)
    {
        if (!Auth::user()->hasRole('alcalde') && !Auth::user()->hasRole('contratista')) {
            abort(403);
        }
        $nuevoEstado = $request->input('estado');
        if (!in_array($nuevoEstado, ['pendiente', 'revision', 'aprobada'])) {
            return back()->with('error', 'Estado inválido.');
        }
        $cuenta->estado = $nuevoEstado;
        $cuenta->save();
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function show(CuentaCobro $cuenta)
    {
        return view('cuentas-cobro.show', compact('cuenta'));
    }
    public function edit(CuentaCobro $cuenta)
    {
        return view('cuentas-cobro.edit', compact('cuenta'));
    }
    public function update(Request $request, CuentaCobro $cuenta)
    {
        $validated = $request->validate([
            'nombre_cobrador'    => 'required|string|max:255',
            'documento_cobrador' => 'required|string|max:20',
            'direccion_cobrador' => 'required|string|max:500',
            'telefono_cobrador'  => 'required|string|max:20',
            'email_cobrador'     => 'required|email',
            'nombre_cliente'     => 'required|string|max:255',
            'documento_cliente'  => 'required|string|max:20',
            'monto'              => 'required|numeric|min:0',
            'descripcion'        => 'nullable|string',
            'fecha_emision'      => 'nullable|date',
        ]);
        $cuenta->update($validated);
        return redirect()->route('cuenta.cobro.index')->with('success', 'Cuenta actualizada correctamente.');
    }
    public function destroy(CuentaCobro $cuenta)
    {
        $cuenta->delete();
        return redirect()->route('cuenta.cobro.index')->with('success', 'Cuenta eliminada correctamente.');
    }
}
