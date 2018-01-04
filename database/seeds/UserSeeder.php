<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => env('MY_NAME'),
            'email'    => env('MY_EMAIL'),
            'password' => bcrypt(env('MY_PASS')),
        ]);
    }
}
