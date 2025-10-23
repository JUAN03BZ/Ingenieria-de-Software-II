<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    public function __construct()
    {
        // Asegura usuario autenticado antes de cualquier acción
        $this->middleware('auth');
    }

    // Helpers internos para evitar depender de métodos inexistentes en User
    private function currentUser(): ?User
    {
        $user = Auth::user();
        if ($user) {
            $user->loadMissing('role'); // requiere belongsTo('role') en User
        }
        return $user;
    }

    private function userHasRole(string|array $roles): bool
    {
        $user = $this->currentUser();
        if (!$user || !$user->role) {
            return false;
        }
        $roles = (array) $roles;
        return in_array($user->role->name, $roles, true);
    }

    private function isAdmin(): bool
    {
        // Define “admin” como rol alcalde para este módulo
        return $this->userHasRole('alcalde');
    }

    public function index()
    {
        if (!$this->isAdmin()) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        // Requiere que el modelo Roles tenga la relación users(): hasMany(User::class, 'role_id')
        $roles = Roles::withCount('users')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        if (!$this->userHasRole('alcalde')) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para crear roles.');
        }

        $availablePermissions = $this->getAvailablePermissions();
        return view('roles.create', compact('availablePermissions'));
    }

    public function store(Request $request)
    {
        if (!$this->userHasRole('alcalde')) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para crear roles.');
        }

        $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name|regex:/^[a-z_]+$/',
            'description' => 'required|string|max:500',
            'permissions' => 'array'
        ]);

        $role = Roles::create([
            'name'        => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function show(Roles $role)
    {
        if (!$this->isAdmin()) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para ver esta información.');
        }

        $users = $role->users()->paginate(10); // requiere users() en Roles
        $availablePermissions = $this->getAvailablePermissions();

        return view('roles.show', compact('role', 'users', 'availablePermissions'));
    }

    public function edit(Roles $role)
    {
        if (!$this->userHasRole('alcalde')) {
            return redirect()->route('roles.index')->with('error', 'No tienes permisos para editar roles.');
        }

        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Roles $role)
    {
        if (!$this->userHasRole('alcalde')) {
            return redirect()->route('roles.index')->with('error', 'No tienes permisos para actualizar roles.');
        }

        $isSystemRole = in_array($role->name, ['contratista', 'supervisor', 'alcalde', 'ordenador_gasto', 'tesoreria', 'contratacion'], true);

        $rules = ['permissions' => 'array'];

        if (!$isSystemRole) {
            $rules = array_merge($rules, [
                'name'        => 'required|string|max:255|regex:/^[a-z_]+$/|unique:roles,name,' . $role->id,
                'description' => 'required|string|max:500',
            ]);
        } else {
            $rules = array_merge($rules, [
                'name'        => 'required|string',
                'description' => 'required|string',
            ]);
        }

        $validated = $request->validate($rules);

        if ($isSystemRole) {
            $role->permissions = $request->input('permissions', []);
        } else {
            $role->name        = $request->input('name');
            $role->description = $request->input('description');
            $role->permissions = $request->input('permissions', []);
        }

        $role->save();

        return redirect()->route('roles.show', $role->id)->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Roles $role)
    {
        if (!$this->userHasRole('alcalde')) {
            return redirect()->route('roles.index')->with('error', 'No tienes permisos para eliminar roles.');
        }

        if (in_array($role->name, ['contratista', 'supervisor', 'alcalde', 'ordenador_gasto', 'tesoreria', 'contratacion'], true)) {
            return redirect()->route('roles.index')->with('error', 'No se pueden eliminar roles del sistema.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar un rol con usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    public function assignRole(Request $request)
    {
        if (!$this->userHasRole(['alcalde', 'contratacion'])) {
            return response()->json(['success' => false, 'error' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::find($data['user_id']);
        $user->role_id = $data['role_id'];
        $user->save();

        return response()->json(['success' => true]);
    }

    public function removeRole(Request $request)
    {
        if (!$this->userHasRole(['alcalde', 'contratacion'])) {
            return response()->json(['success' => false, 'error' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($data['user_id']);
        $user->role_id = null;
        $user->save();

        return response()->json(['success' => true]);
    }

    // NUEVOS: aprobación y asignación de roles por admin
    public function usuariosPendientes()
    {
        if (!$this->userHasRole('alcalde')) {
            abort(403);
        }

        $usuarios = User::where('is_approved', false)->orderBy('created_at', 'desc')->paginate(15);
        $roles = Roles::orderBy('name')->get(['id', 'name']);
        return view('usuarios.pendientes', compact('usuarios', 'roles'));
    }

    public function aprobarUsuario(Request $request, User $usuario)
    {
        if (!$this->userHasRole('alcalde')) {
            abort(403);
        }

        $usuario->is_approved = true;
        $usuario->approved_at = now();
        $usuario->save();

        return back()->with('success', 'Usuario aprobado correctamente.');
    }

    public function asignarRol(Request $request, User $usuario)
    {
        if (!$this->userHasRole('alcalde')) {
            abort(403);
        }

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $usuario->role_id = $data['role_id'];
        $usuario->save();

        return back()->with('success', 'Rol asignado correctamente.');
    }

    private function getAvailablePermissions()
    {
        return [
            'create_cuenta_cobro',
            'view_cuenta_cobro',
            'view_own_cuenta_cobro',
            'view_all_cuenta_cobro',
            'edit_own_cuenta_cobro',
            'review_cuenta_cobro',
            'approve_cuenta_cobro',
            'reject_cuenta_cobro',
            'final_approval',
            'upload_documents',
            'view_documents',
            'view_contract_info',
            'manage_contracts',
            'contract_validation',
            'authorize_payment',
            'process_payment',
            'generate_checks',
            'bank_transfers',
            'payment_confirmation',
            'generate_payment_orders',
            'view_budget',
            'manage_budget',
            'view_reports',
            'financial_reports',
            'view_financial_reports',
            'contract_reports',
            'manage_users',
            'manage_contractors',
            'contractor_registration',
            'system_admin',
            'add_comments',
            'request_corrections',
            'override_decisions',
        ];
    }
}
