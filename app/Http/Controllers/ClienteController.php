<?php
// app/Http/Controllers/ClienteController.php [web:264]

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::latest()->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'documento' => 'required|string|max:20|unique:clientes,documento',
            'email'     => 'required|email|max:255|unique:clientes,email',
            'telefono'  => 'nullable|string|max:50',
        ]);

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $cliente = Cliente::create($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente con ID: ' . $cliente->id);
    }
}
