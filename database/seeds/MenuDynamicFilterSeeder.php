<?php

use Illuminate\Database\Seeder;

class MenuDynamicFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMenu = [
        [
        'id'=>'93',
          'parent_id' => 8,
          'order' => 42,
          'label' => 'Dynamic Filter',
          'icon' => '',
          'route_name' => '',
          'status' => true
        ],
        [
            'id'=>'94',
          'parent_id' => 93,
          'order' => 43,
          'label' => 'Tabel Sumber',
          'icon' => '',
          'route_name' => 'referensi.dynamicfilter.tabelsumber.index',
          'status' => true
        ],
        [
            'id'=>'95',
          'parent_id' => 93,
          'order' => 44,
          'label' => 'Standar Value',
          'icon' => '',
          'route_name' => 'referensi.dynamicfilter.standarvalue.index',
          'status' => true
        ],
        [
            'id'=>'96',
          'parent_id' => 93,
          'order' => 45,
          'label' => 'Operator',
          'icon' => '',
          'route_name' => 'referensi.dynamicfilter.operator.index',
          'status' => true
        ],
        [
            'id'=>'97',
          'parent_id' => 93,
          'order' => 46,
          'label' => 'Parameter',
          'icon' => '',
          'route_name' => 'referensi.dynamicfilter.parameter.index',
          'status' => true
        ],

      ];
      DB::table('menus')->insert($dataMenu);

      $dataRoleMenu = [
          [
              'role_id'=>1,
              'menu_id'=>93,
          ],
          [
              'role_id'=>1,
              'menu_id'=>94,
          ],
          [
              'role_id'=>1,
              'menu_id'=>95,
          ],
          [
              'role_id'=>1,
              'menu_id'=>96,
          ],
          [
              'role_id'=>1,
              'menu_id'=>97,
          ],
      ];

      DB::table('role_menu')->insert($dataRoleMenu);
    }
}
