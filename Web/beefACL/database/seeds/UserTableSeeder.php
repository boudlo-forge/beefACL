<?php

use Illuminate\Database\Seeder;
use app\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

		$data = [
            'name'          => 'Default Admin',
            'email'         => 'admin@beefacl.local',
            'password'	    => Hash::make('beefadmin'),
            'status_id'     => 1,
            'role_flags'    => User::ROLE_SUPER_ADMIN,
            'flags'         => User::FLAG_CHANGE_PASSWORD,
        ];

        DB::table('users')->insert($data);
    }
}
