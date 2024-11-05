<?php

use Illuminate\Database\Seeder;

class RolesTableSeed extends Seeder
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
          'name' => 'Admin',
          'guard_name' => 'web'
        ]
      ];
      DB::table('roles')->insert($data);
    }
}
