<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = config('seed.admin.email', env('ADMIN_EMAIL', 'admin@example.com')); // .env
        $name  = 'Andres Acevedo'; // nombre fijo solicitado
        $providedPassword = env('ADMIN_PASSWORD'); // si está definido, rota password

        $alcaldeRole = Roles::firstWhere('name', 'alcalde');
        if (!$alcaldeRole) {
            $this->command->error('El rol de alcalde no existe. Ejecuta primero el RoleSeeder.');
            return;
        }

        // Si NO existe usuario: crear con password en el INSERT (evita error 1364)
        $existing = User::where('email', $email)->first();

        if (!$existing) {
            $plain = $providedPassword ?: Str::password(16);
            $admin = User::create([
                'name'             => $name,
                'email'            => $email,
                'password'         => Hash::make($plain), // en el INSERT
                'role_id'          => $alcaldeRole->id,
                'email_verified_at'=> now(),
                'is_approved'      => true,
                'approved_at'      => now(),
            ]);

            if (app()->environment('local') && !$providedPassword) {
                $this->command->warn("Admin creado: {$email} (contraseña temporal solo en local)");
                $this->command->warn("Contraseña temporal: {$plain}");
            }

            $this->command->info("Usuario admin '{$email}' (Andres Acevedo) creado y aprobado.");
            return;
        }

        // Si EXISTE: actualizar datos sin tocar password, salvo que definas ADMIN_PASSWORD
        $existing->fill([
            'name'             => $name,
            'role_id'          => $alcaldeRole->id,
            'email_verified_at'=> $existing->email_verified_at ?? now(),
            'is_approved'      => true,
            'approved_at'      => $existing->approved_at ?? now(),
        ])->save();

        if ($providedPassword) {
            $existing->password = Hash::make($providedPassword);
            $existing->save();
        }

        $this->command->info("Usuario admin '{$email}' (Andres Acevedo) actualizado y aprobado.");
    }
}
