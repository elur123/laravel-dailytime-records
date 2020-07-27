<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_access')
        ->insert([
            'fullname' => 'Admin Admin',
            'usertype' => 1,
            'uname' => 'admin123',
            'pass' =>  Hash::make('administrator')
        ]);
    }
}
