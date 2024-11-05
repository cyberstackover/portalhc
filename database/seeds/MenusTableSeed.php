<?php

use Illuminate\Database\Seeder;

class MenusTableSeed extends Seeder
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
          'parent_id' => 0,
          'order' => 0,
          'label' => 'Home',
          'icon' => 'fa fa-gear',
          'route_name' => 'home',
          'status' => true
        ]
      ];
      DB::table('menus')->insert($data);
    }
}
