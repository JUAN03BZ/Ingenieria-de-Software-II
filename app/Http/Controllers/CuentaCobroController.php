<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaCobroController extends Controller
{
    // Mostrar formulario de creación (policy: create => contratista | alcalde)
    public function create()
    {
        $this->authorize('create', CuentaCobro::class);
        return view('cuentas-cobro.crear');
    }

    // Guardar nueva cuenta (creador = usuario autenticado; estado inicial pendiente)
    public function store(Request $request)
    {
        $this->authorize('create', CuentaCobro::class);

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
        $validated['estado']  = 'pendiente';

        CuentaCobro::create($validated);

        return redirect()->route('cuenta.cobro.pendientes')
            ->with('success', 'Cuenta de cobro creada exitosamente.');
    }

    // Listado general (todos pueden ver)
    public function index()
    {
        $cuentas = CuentaCobro::orderBy('created_at', 'desc')->paginate(10);
        return view('cuentas-cobro.index', compact('cuentas'));
    }

    // Listado de pendientes (todos pueden ver)
    public function pendientes()
    {
        $cuentas = CuentaCobro::whereIn('estado', ['pendiente', 'revision'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cuentas-cobro.pendientes', compact('cuentas'));
    }

    // Cambiar estado (solo alcalde)
    public function cambiarEstado(Request $request, CuentaCobro $cuenta)
    {
        if (!Auth::user()->isAlcalde()) {
            abort(403);
        }

        $data = $request->validate([
            'estado' => 'required|in:pendiente,revision,aprobada,rechazada,pagada',
        ]);

        $cuenta->estado = $data['estado'];
        $cuenta->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    // Aprobar (solo alcalde)
    public function aprobar(CuentaCobro $cuenta)
    {
        if (!Auth::user()->isAlcalde()) {
            abort(403);
        }

        $cuenta->estado = 'aprobada';
        $cuenta->save();

        return back()->with('success', 'Cuenta aprobada correctamente.');
    }

    // Rechazar (solo alcalde)
    public function rechazar(Request $request, CuentaCobro $cuenta)
    {
        if (!Auth::user()->isAlcalde()) {
            abort(403);
        }

        $data = $request->validate([
            'observaciones' => 'required|string|max:500',
        ]);

        $cuenta->estado = 'rechazada';
        $cuenta->observaciones = $data['observaciones'];
        $cuenta->save();

        return back()->with('success', 'Cuenta rechazada correctamente.');
    }

    // Ver detalle (todos pueden ver vía policy)
    public function show(CuentaCobro $cuenta)
    {
        $this->authorize('view', $cuenta);
        return view('cuentas-cobro.show', compact('cuenta'));
    }

    // Editar (contratista propia | alcalde cualquiera)
    public function edit(CuentaCobro $cuenta)
    {
        $this->authorize('update', $cuenta);
        return view('cuentas-cobro.edit', compact('cuenta'));
    }

    // Actualizar (contratista propia | alcalde cualquiera)
    public function update(Request $request, CuentaCobro $cuenta)
    {
        $this->authorize('update', $cuenta);

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

        return redirect()->route('cuenta.cobro.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    // Eliminar (contratista propia | alcalde cualquiera)
    public function destroy(CuentaCobro $cuenta)
    {
        $this->authorize('delete', $cuenta);

        $cuenta->delete();

        return redirect()->route('cuenta.cobro.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }
}
