<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    // Método para listar clientes (mínimo: devuelve una vista vacía por ahora)
    public function index()
    {
        // Temporal: Si no tienes modelo, solo muestra un mensaje
        // Después, usa: $clientes = Cliente::all(); return view('clientes.index', compact('clientes'));
        return view('clientes.index'); // Crea esta vista si no existe (ver Paso 4)
    }

    // Método para mostrar formulario de crear cliente
    public function create()
    {
        return view('clientes.create'); // Crea esta vista si no existe (ver Paso 4)
    }

    // Método para guardar cliente (el que ya tenías, ajustado)
    public function guardar(Request $request)
    {
        // Validación básica (ajusta según tus necesidades)
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:20',
            'email' => 'required|email',
            // Agrega más campos si los tienes
        ]);

        // Aquí guardarías en BD (ej. Cliente::create($validated);)
        // Por ahora, solo redirige con éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente guardado exitosamente (simulado).');
    }
}