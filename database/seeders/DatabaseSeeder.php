<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $perfilAdmin = Perfil::create([
            'descricao' => 'Administrador',
            'permissoes' => ['total'] // Uma tag genérica para o Admin
        ]);
        User::create([
            'name' => 'Admin do Sistema',
            'email' => 'admin@estoque.com',
            'password' => Hash::make('senha123'), // Criptografa a senha "senha123"
            'perfil_id' => $perfilAdmin->id,
        ]);
        $this->call([
        ProdutoSeeder::class,
        ]);
    }
}
