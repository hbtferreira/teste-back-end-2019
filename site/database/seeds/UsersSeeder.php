<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Popula a tabela de usuários.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name' => 'Din Digital',
            'email' => 'suporte@dindigital.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
