<?php

use Illuminate\Database\Seeder;

class RoleMenuTableSeed extends Seeder
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
          'role_id' => 1,
          'menu_id' => 1
        ]
      ];
      DB::table('role_menu')->insert($data);
    }
}
