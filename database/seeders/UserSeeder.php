<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = [
            'id' => Uuid::uuid4(),
            'name' => 'Hendra',
            'username' => 'hendra',
            'email' => 'hendra@gmail.com',
            'password' => Hash::make('password')
        ];

        DB::table('user')->insert($user);
    }
}
