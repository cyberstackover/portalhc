<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SebagianPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $max_id = Permission::select(DB::raw('MAX(id) as max'))->first();
        $max = ((int) $max_id->max) + 1;
        DB::unprepared('ALTER SEQUENCE permissions_id_seq RESTART WITH '.$max);

        $admin = Role::where('name', 'Admin')->first();
        
        Permission::firstOrCreate(['name' => 'kategoripemberhentian-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategoripemberhentian-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategoripemberhentian-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategoripemberhentian-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('kategoripemberhentian-list');
        $admin->givePermissionTo('kategoripemberhentian-create');
        $admin->givePermissionTo('kategoripemberhentian-edit');
        $admin->givePermissionTo('kategoripemberhentian-delete');
        
        Permission::firstOrCreate(['name' => 'kategorimobility-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategorimobility-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategorimobility-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kategorimobility-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('kategorimobility-list');
        $admin->givePermissionTo('kategorimobility-create');
        $admin->givePermissionTo('kategorimobility-edit');
        $admin->givePermissionTo('kategorimobility-delete');
        
        Permission::firstOrCreate(['name' => 'jenisfilependukung-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jenisfilependukung-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jenisfilependukung-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jenisfilependukung-delete', 'guard_name' => 'web']);
        
        $admin->givePermissionTo('jenisfilependukung-list');
        $admin->givePermissionTo('jenisfilependukung-create');
        $admin->givePermissionTo('jenisfilependukung-edit');
        $admin->givePermissionTo('jenisfilependukung-delete');

        Permission::firstOrCreate(['name' => 'asalinstansi-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'asalinstansi-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'asalinstansi-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'asalinstansi-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('asalinstansi-list');
        $admin->givePermissionTo('asalinstansi-create');
        $admin->givePermissionTo('asalinstansi-edit');
        $admin->givePermissionTo('asalinstansi-delete');

        Permission::firstOrCreate(['name' => 'alasanpemberhentian-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'alasanpemberhentian-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'alasanpemberhentian-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'alasanpemberhentian-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('alasanpemberhentian-list');
        $admin->givePermissionTo('alasanpemberhentian-create');
        $admin->givePermissionTo('alasanpemberhentian-edit');
        $admin->givePermissionTo('alasanpemberhentian-delete');

        Permission::firstOrCreate(['name' => 'komposisiprofesional-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'komposisiprofesional-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'komposisiprofesional-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'komposisiprofesional-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('komposisiprofesional-list');
        $admin->givePermissionTo('komposisiprofesional-create');
        $admin->givePermissionTo('komposisiprofesional-edit');
        $admin->givePermissionTo('komposisiprofesional-delete');
        
        Permission::firstOrCreate(['name' => 'targetasalinstansi-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'targetasalinstansi-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'targetasalinstansi-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'targetasalinstansi-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('targetasalinstansi-list');
        $admin->givePermissionTo('targetasalinstansi-create');
        $admin->givePermissionTo('targetasalinstansi-edit');
        $admin->givePermissionTo('targetasalinstansi-delete');

        Permission::firstOrCreate(['name' => 'faktorpenghasilan-list', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'faktorpenghasilan-create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'faktorpenghasilan-edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'faktorpenghasilan-delete', 'guard_name' => 'web']);

        $admin->givePermissionTo('faktorpenghasilan-list');
        $admin->givePermissionTo('faktorpenghasilan-create');
        $admin->givePermissionTo('faktorpenghasilan-edit');
        $admin->givePermissionTo('faktorpenghasilan-delete');
        
        return $this->command->info('Sebagian Permission Seeded');
    }
}
