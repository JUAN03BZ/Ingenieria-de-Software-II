<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    // Método para listar clientes (mínimo: devuelve una vista vacía por ahora)
    public function index()
    {
        return view('clientes.index'); 
    }

    // Método para mostrar formulario de crear cliente
    public function create()
    {
        return view('clientes.create'); // Crea esta vista si no existe (ver Paso 4)
    }

    // Método para guardar cliente 
    public function guardar(Request $request)
    {
        // Validación básica 
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:20',
            'email' => 'required|email',
            
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente guardado exitosamente (simulado).');
    }
}