<?php

namespace App\Http\Controllers;

use App\Models\CuentaCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaCobroController extends Controller
{
    // Mostrar formulario de creación
    public function create()
    {
        $this->authorize('create', CuentaCobro::class);
        return view('cuentas-cobro.crear');
    }

    // Guardar nueva cuenta
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
        $validated['fase']    = 'creada';

        $cuenta = CuentaCobro::create($validated);

        $cuenta->flujos()->create([
            'user_id'   => Auth::id(),
            'rol'       => Auth::user()->role->name ?? 'contratista',
            'accion'    => 'creada',
            'comentario'=> 'Cuenta registrada',
        ]);

        return redirect()->route('cuenta.cobro.pendientes')
            ->with('success', 'Cuenta de cobro creada exitosamente.');
    }

    // Listado general
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('supervisor')) {
            $cuentas = CuentaCobro::where('fase', 'supervisor')->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->hasRole('contratista')) {
            $cuentas = $user->cuentasCobro()->whereIn('fase', ['creada', 'finalizada'])->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->hasRole('tesoreria')) {
            $cuentas = CuentaCobro::where('fase', 'tesoreria')->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->hasRole('contratacion')) {
            $cuentas = CuentaCobro::where('fase', 'contratacion')->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->hasRole('ordenador_gasto')) {
            $cuentas = CuentaCobro::where('fase', 'ordenador')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $cuentas = CuentaCobro::orderBy('created_at', 'desc')->paginate(10);
        }

        return view('cuentas-cobro.index', compact('cuentas'));
    }

    // Listado de pendientes
    public function pendientes()
    {
        $cuentas = CuentaCobro::whereIn('estado', ['pendiente', 'revision'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('cuentas-cobro.pendientes', compact('cuentas'));
    }

    // Contratista o alcalde: enviar a supervisor
    public function enviarASupervision(CuentaCobro $cuenta)
    {
        $this->authorize('update', $cuenta);
        $cuenta->fase   = 'supervisor';
        $cuenta->estado = 'pendiente';
        $cuenta->save();

        $cuenta->flujos()->create([
            'user_id'   => auth()->id(),
            'rol'       => auth()->user()->role->name ?? 'contratista',
            'accion'    => 'enviado',
            'comentario'=> 'Enviada a supervisor',
        ]);

        return back()->with('success', 'Enviada a revisión del supervisor.');
    }

    // SUPERVISOR: aprobar o rechazar
    public function supervisorDecision(Request $request, CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('supervisor')) abort(403);

        $data = $request->validate([
            'decision'   => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if ($data['decision'] === 'rechazado') {
            $cuenta->estado = 'rechazada';
            $cuenta->fase   = 'finalizada';
        } else {
            $cuenta->estado = 'pendiente';
            $cuenta->fase   = 'contratacion';
        }

        $cuenta->save();

        $cuenta->flujos()->create([
            'user_id'   => auth()->id(),
            'rol'       => 'supervisor',
            'accion'    => $data['decision'],
            'comentario'=> $data['comentario'],
        ]);

        return back()->with('success', 'Decisión de supervisor registrada.');
    }

    // Contratación aprueba o rechaza
    public function contratacionDecision(Request $request, CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('contratacion')) abort(403);

        $data = $request->validate([
            'decision'   => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if ($data['decision'] === 'rechazado') {
            $cuenta->estado = 'rechazada';
            $cuenta->fase   = 'finalizada';
        } else {
            $cuenta->estado = 'pendiente';
            $cuenta->fase   = 'tesoreria';
        }

        $cuenta->save();

        $cuenta->flujos()->create([
            'user_id'   => auth()->id(),
            'rol'       => 'contratacion',
            'accion'    => $data['decision'],
            'comentario'=> $data['comentario'],
        ]);

        return back()->with('success', 'Decisión de contratación registrada.');
    }

    // Tesorería aprueba o rechaza, verifica fondos
    public function tesoreriaDecision(Request $request, CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('tesoreria')) abort(403);

        $data = $request->validate([
            'decision'   => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|max:1000',
            'hay_fondos' => 'required|boolean',
        ]);

        if ($data['decision'] === 'rechazado' || !$data['hay_fondos']) {
            $cuenta->estado = 'rechazada';
            $cuenta->fase   = 'finalizada';
        } else {
            $cuenta->estado = 'pendiente';
            $cuenta->fase   = 'ordenador';
        }

        $cuenta->save();

        $cuenta->flujos()->create([
            'user_id'   => auth()->id(),
            'rol'       => 'tesoreria',
            'accion'    => $data['decision'],
            'comentario'=> $data['comentario'] ?? ($data['hay_fondos'] ? 'Con fondos' : 'Sin fondos'),
        ]);

        return back()->with('success', 'Decisión de tesorería registrada.');
    }

    // Ordenador de gasto aprueba final o rechaza
    public function ordenadorDecision(Request $request, CuentaCobro $cuenta)
    {
        if (!auth()->user()->hasRole('ordenador_gasto')) abort(403);

        $data = $request->validate([
            'decision'   => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if ($data['decision'] === 'rechazado') {
            $cuenta->estado = 'rechazada';
        } else {
            $cuenta->estado = 'aprobada';
        }

        $cuenta->fase = 'finalizada';
        $cuenta->save();

        $cuenta->flujos()->create([
            'user_id'   => auth()->id(),
            'rol'       => 'ordenador_gasto',
            'accion'    => $data['decision'],
            'comentario'=> $data['comentario'],
        ]);

        return back()->with('success', 'Decisión final registrada.');
    }

    // Cambio de estado manual solo alcaldía (legacy)
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

    // Ver detalle
    public function show(CuentaCobro $cuenta)
    {
        $this->authorize('view', $cuenta);
        $cuenta->load('flujos.user');
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

    // Eliminar
    public function destroy(CuentaCobro $cuenta)
    {
        $this->authorize('delete', $cuenta);
        $cuenta->delete();

        return redirect()->route('cuenta.cobro.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }

    public function dashboardFases()
    {
        $user = auth()->user();

        if ($user->hasRole('contratista')) {
            $cuentas = $user->cuentasCobro()
                ->whereIn('fase', ['creada', 'finalizada'])
                ->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('supervisor')) {
            $cuentas = CuentaCobro::where('fase', 'supervisor')
                ->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('contratacion')) {
            $cuentas = CuentaCobro::where('fase', 'contratacion')
                ->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('tesoreria')) {
            $cuentas = CuentaCobro::where('fase', 'tesoreria')
                ->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('ordenador_gasto')) {
            $cuentas = CuentaCobro::where('fase', 'ordenador')
                ->orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('alcalde')) {
            $cuentas = CuentaCobro::where('estado', 'pendiente')
                ->orderBy('created_at', 'desc')->get();
        } else {
            $cuentas = collect();
        }

        $actividadesRecientes = CuentaCobro::latest()->take(5)->get();

        return view('dashboard', compact('cuentas', 'actividadesRecientes'));
    }
}
