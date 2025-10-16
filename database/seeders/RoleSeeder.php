<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'contratista',
                'description' => 'Contratista - Presenta cuentas de cobro',
                'permissions' => [
                    'create_cuenta_cobro',
                    'view_own_cuenta_cobro',
                    'edit_own_cuenta_cobro',
                    'upload_documents',
                    'view_contract_info'
                ]
            ],
            [
                'name' => 'supervisor',
                'description' => 'Supervisor - Revisa y valida las cuentas de cobro',
                'permissions' => [
                    'view_cuenta_cobro',
                    'review_cuenta_cobro',
                    'approve_cuenta_cobro',
                    'reject_cuenta_cobro',
                    'add_comments',
                    'request_corrections'
                ]
            ],
            [
                'name' => 'alcalde',
                'description' => 'Alcalde - Aprobación ejecutiva final',
                'permissions' => [
                    'view_all_cuenta_cobro',
                    'final_approval',
                    'override_decisions',
                    'view_reports',
                    'manage_users',
                    'system_admin'
                ]
            ],
            [
                'name' => 'ordenador_gasto',
                'description' => 'Ordenador del Gasto - Autoriza los pagos',
                'permissions' => [
                    'view_cuenta_cobro',
                    'authorize_payment',
                    'view_budget',
                    'manage_budget',
                    'generate_payment_orders',
                    'view_financial_reports'
                ]
            ],
            [
                'name' => 'tesoreria',
                'description' => 'Tesorería - Procesa los pagos',
                'permissions' => [
                    'view_cuenta_cobro',
                    'process_payment',
                    'generate_checks',
                    'bank_transfers',
                    'payment_confirmation',
                    'financial_reports'
                ]
            ],
            [
                'name' => 'contratacion',
                'description' => 'Contratación - Administra contratos y contratistas',
                'permissions' => [
                    'manage_contracts',
                    'manage_contractors',
                    'view_all_cuenta_cobro',
                    'contract_validation',
                    'contractor_registration',
                    'contract_reports'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Roles::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'description' => $roleData['description'],
                    'permissions' => $roleData['permissions']
                ]
            );
        }

        $this->command->info('Roles creados exitosamente.');
    }
}