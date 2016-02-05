<?php

use Illuminate\Database\Seeder;

class CarbuncleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        Carbuncle::getUserProvider()->create(array(
            'email'    => 'admin@admin.com',
            'username' => 'admin',
            'password' => 'carbuncleadmin',
            'activated' => 1,
        ));

        Carbuncle::getUserProvider()->create(array(
            'email'    => 'user@user.com',
            'username' => '',
            'password' => 'carbuncleuser',
            'activated' => 1,
        ));
    }
}
