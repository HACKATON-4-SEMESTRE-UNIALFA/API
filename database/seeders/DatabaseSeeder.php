<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Executa o seeder para criar usuários padrões.
     */
    public function run(): void
    {
        // Usuário administrador
        Usuario::create([
            'nome' => 'Administrador do Sistema',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('Admin@123'),
            'telefone' => '(44)99999-9999',
            'cpf' => '11111111111',
            'isAdmin' => true,
            'isUser' => true,
        ]);

        // Usuário comum
        Usuario::create([
            'nome' => 'Usuário Padrão',
            'email' => 'usuario@sistema.com',
            'password' => Hash::make('User@123'),
            'telefone' => '(44)98888-8888',
            'cpf' => '22222222222',
            'isAdmin' => false,
            'isUser' => true,
        ]);
    }
}
