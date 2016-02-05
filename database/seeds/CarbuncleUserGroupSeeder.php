<?php

use Illuminate\Database\Seeder;

class CarbuncleUserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_groups')->delete();

        $userUser = Carbuncle::getUserProvider()->findByLogin('user@user.com');
        $adminUser = Carbuncle::getUserProvider()->findByLogin('admin@admin.com');

        $userGroup = Carbuncle::getGroupProvider()->findByName('Users');
        $adminGroup = Carbuncle::getGroupProvider()->findByName('Admins');

        // Assign the groups to the users
        $userUser->addGroup($userGroup);
        $adminUser->addGroup($userGroup);
        $adminUser->addGroup($adminGroup);
    }
}
