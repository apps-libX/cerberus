<?php

use Illuminate\Database\Seeder;

class CerberusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Eloquent::unguard();

        // $this->call('UserTableSeeder');
        $this->call('CarbuncleGroupSeeder');
        $this->call('CarbuncleUserSeeder');
        $this->call('CarbuncleUserGroupSeeder');
    }
}
