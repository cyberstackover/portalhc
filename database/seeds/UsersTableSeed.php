<?php

use Illuminate\Database\Seeder;

class UsersTableSeed extends Seeder
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
          'username' => 'zalfrie',
          'name' => 'Zalfrie Januardi',
          'email' => 'zalfrie@bumn.go.id',
          'password' => bcrypt('password')
        ]
      ];
      DB::table('users')->insert($data);
    }
}
