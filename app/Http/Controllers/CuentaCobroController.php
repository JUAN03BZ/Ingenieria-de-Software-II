<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaCobroController extends Controller
{
    public function create()
    {
        return view('cuentas-cobro.crear');
    }

    public function store(Request $request)
    {
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

        $validated['user_id'] = Auth::id();
        $validated['estado'] = 'pendiente';

        CuentaCobro::create($validated);

        return redirect()->route('cuenta.cobro.index')->with('success', 'Cuenta de cobro creada exitosamente.');
    }

    public function index()
    {
        if (
            Auth::user()->hasRole('alcalde') ||
            Auth::user()->hasRole('ordenador_gasto')
        ) {
            $cuentas = CuentaCobro::orderBy('created_at', 'desc')->paginate(10);
        } else {
            $cuentas = CuentaCobro::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('cuentas-cobro.index', compact('cuentas'));
    }

    // Solo alcalde/ordenador pueden ver cuentas pendientes
    public function pendientes()
    {
        // Seguridad: Solo alcalde y ordenador de gasto pueden ver este listado
        if (!Auth::user()->hasRole('alcalde') && !Auth::user()->hasRole('ordenador_gasto')) {
            abort(403, 'No autorizado.');
        }

        $cuentas = CuentaCobro::where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('cuentas-cobro.pendientes', compact('cuentas'));
    }

    // Solo ordenador de gasto (protegido en rutas también)
    public function pendientesOrdenador()
    {
        if (!Auth::user()->hasRole('ordenador_gasto') && !Auth::user()->hasRole('alcalde')) {
            abort(403, 'No autorizado.');
        }

        $cuentas = CuentaCobro::where('estado', 'pendiente_ordenador')
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('cuentas-cobro.pendientes_ordenador', compact('cuentas'));
    }

    public function show(CuentaCobro $cuenta)
    {
        $esAlcalde = Auth::user()->hasRole('alcalde');
        $esOrdenador = Auth::user()->hasRole('ordenador_gasto');
        if ($cuenta->user_id !== Auth::id() && !$esAlcalde && !$esOrdenador) {
            abort(403);
        }
        return view('cuentas-cobro.show', compact('cuenta'));
    }

    public function edit(CuentaCobro $cuenta)
    {
        $esAlcalde = Auth::user()->hasRole('alcalde');
        if (($cuenta->user_id !== Auth::id() && !$esAlcalde) || $cuenta->estado !== 'pendiente') {
            abort(403);
        }
        return view('cuentas-cobro.edit', compact('cuenta'));
    }

    public function update(Request $request, CuentaCobro $cuenta)
    {
        $esAlcalde = Auth::user()->hasRole('alcalde');
        if (($cuenta->user_id !== Auth::id() && !$esAlcalde) || $cuenta->estado !== 'pendiente') {
            abort(403);
        }

        $validated = $request->validate([
            'nombre_cobrador' => 'required|string|max:255',
            'documento_cobrador' => 'required|string|max:20',
            'direccion_cobrador' => 'required|string|max:500',
            'telefono_cobrador' => 'required|string|max:20',
            'email_cobrador' => 'required|email',
            'nombre_cliente' => 'required|string|max:255',
            'documento_cliente' => 'required|string|max:20',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'fecha_emision' => 'nullable|date',
        ]);

        $cuenta->update($validated);

        return redirect()->route('cuenta.cobro.index')->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(CuentaCobro $cuenta)
    {
        $esAlcalde = Auth::user()->hasRole('alcalde');
        if (($cuenta->user_id !== Auth::id() && !$esAlcalde) || $cuenta->estado !== 'pendiente') {
            abort(403);
        }
        $cuenta->delete();
        return redirect()->route('cuenta.cobro.index')->with('success', 'Cuenta eliminada correctamente.');
    }

    public function aprobar(CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('ordenador_gasto')) {
            abort(403);
        }
        if ($cuenta->estado !== 'pendiente_ordenador') {
            return back()->with('error', 'Solo se pueden aprobar cuentas en estado pendiente para ordenador de gasto.');
        }
        $cuenta->estado = 'aprobada';
        $cuenta->save();

        return redirect()->route('cuenta.cobro.index')
            ->with('success', 'Cuenta de cobro aprobada correctamente.');
    }

    public function rechazar(Request $request, CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('ordenador_gasto')) {
            abort(403);
        }
        if ($cuenta->estado !== 'pendiente_ordenador') {
            return back()->with('error', 'Solo se pueden rechazar cuentas en estado pendiente para ordenador de gasto.');
        }
        $cuenta->estado = 'rechazada';
        $cuenta->observaciones = $request->input('observaciones');
        $cuenta->save();

        return redirect()->route('cuenta.cobro.index')
            ->with('success', 'Cuenta de cobro rechazada.');
    }
}
