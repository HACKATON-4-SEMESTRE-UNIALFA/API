<?php

namespace Database\Seeders;

use App\Models\Usuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Usuario::firstOrCreate([
            'nome' => 'Test User',
            'email' => 'test1@example.com',
            'password' => Hash::make('password'),   
            'telefone' => '1234567890', 
            'cpf' => '123.456.789-00',
            'isAdmin' => true,
            'isUser' => true,

        ]);
        Usuario::firstOrCreate([
            'nome' => 'Adolf',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),   
            'telefone' => '1234567890', 
            'cpf' => '122.456.789-00',
            'isAdmin' => false,
            'isUser' => true,

        ]);
    }
}
