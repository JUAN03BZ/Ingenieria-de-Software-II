<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente; // Asumiendo que tienes este modelo

class ClienteController extends Controller
{
    // AGREGADO: Mostrar lista de clientes
    public function index()
    {
        $clientes = Cliente::all(); // O filtra por user_id si quieres
        return view('clientes.index', compact('clientes')); // Crea esta vista
    }

    // AGREGADO: Mostrar formulario para crear cliente
    public function create()
    {
        return view('clientes.create'); // Crea esta vista
    }

    // Método que ya tenías: Guardar cliente
    public function guardar(Request $request)
    {
        // Validación básica (ajusta según tus campos)
        $validated = $request->validate([
            'nombre' => 'required|string|max:255', // Asumiendo campos como nombre, documento, etc.
            'documento' => 'required|string|max:20|unique:clientes,documento',
            'email' => 'required|email|unique:clientes,email',
            // Agrega más campos según tu tabla
        ]);

        // Opcional: Asigna user_id si está logueado
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        // Guardar en BD
        $cliente = Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente con ID: ' . $cliente->id);
    }
}