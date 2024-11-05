<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        [
          'name' => 'user-list',
          'guard_name' => 'web'
        ],
        [
          'name' => 'role-list',
          'guard_name' => 'web'
        ],
        [
          'name' => 'role-create',
          'guard_name' => 'web'
        ],
        [
          'name' => 'role-edit',
          'guard_name' => 'web'
        ],
        [
          'name' => 'role-delete',
          'guard_name' => 'web'
        ],
        [
          'name' => 'permission-list',
          'guard_name' => 'web'
        ],
        [
          'name' => 'permission-create',
          'guard_name' => 'web'
        ],
        [
          'name' => 'permission-edit',
          'guard_name' => 'web'
        ],
        [
          'name' => 'permission-delete',
          'guard_name' => 'web'
        ],
        [
          'name' => 'menu-list',
          'guard_name' => 'web'
        ],
        [
          'name' => 'menu-create',
          'guard_name' => 'web'
        ],
        [
          'name' => 'menu-edit',
          'guard_name' => 'web'
        ],
        [
          'name' => 'menu-delete',
          'guard_name' => 'web'
        ]
      ];
      DB::table('permissions')->insert($data);
    }
}
