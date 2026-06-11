<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\Compra;
use App\Models\Venda;
use App\Models\Perfil;
use Illuminate\Support\Facades\DB;
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
        $perfilAdmin = Perfil::firstOrCreate(
            ['id' => 1],
            [
                'descricao' => 'Administrador',
                'permissoes' => ['cadastrar_usuarios', 'compras', 'vendas', 'produtos', 'administracao'] 
            ]
        );

        if (!User::where('id', 1)->exists()) {
            User::create([
                'id'            => 1,
                'name'          => 'Administrador Geral',
                'email'         => 'admin@sistema.com',
                'password'      => Hash::make('admin123'), // Altere após o primeiro login
                'perfil_id'     => $perfilAdmin->id,
            ]);
        }
        $fornecedor1 = Fornecedor::create([
            'razao_social' => 'Distribuidora de Peças Central S.A.',
            'nome_fantasia' => 'Central Auto Peças',
            'cnpj' => '11.111.111/0001-11',
            'telefone' => '(11) 3333-1111',
            'email' => 'vendas@centralpecas.com.br',
        ]);

        $fornecedor2 = Fornecedor::create([
            'razao_social' => 'Óleos e Lubrificantes Brasil LTDA',
            'nome_fantasia' => 'Brasil Lubrificantes',
            'cnpj' => '22.222.222/0001-22',
            'telefone' => '(19) 99999-2222',
            'email' => 'contato@brasillub.com.br',
        ]);

        // 3. Seeder de Peças de Carro (Produtos)
        $p1 = Produto::create(['nome' => 'PASTILHA DE FREIO COBREQ', 'marca' => 'COBREQ', 'descricao' => 'Pastilha dianteira universal', 'preco' => 85.50, 'estoque' => 10]);
        $p2 = Produto::create(['nome' => 'ÓLEO DE MOTOR SINTÉTICO 5W30 1L', 'marca' => 'CASTROL', 'descricao' => 'Óleo para motores flex', 'preco' => 45.00, 'estoque' => 24]);
        $p3 = Produto::create(['nome' => 'FILTRO DE ÓLEO WEGA', 'marca' => 'WEGA', 'descricao' => 'Filtro de óleo blindado', 'preco' => 25.00, 'estoque' => 15]);
        $p4 = Produto::create(['nome' => 'VELA DE IGNIÇÃO NGK', 'marca' => 'NGK', 'descricao' => 'Jogo de velas resistivas', 'preco' => 60.00, 'estoque' => 8]);
        $p5 = Produto::create(['nome' => 'BATERIA MOURA 60AH', 'marca' => 'MOURA', 'descricao' => 'Bateria 12V 60Ah', 'preco' => 420.00, 'estoque' => 5]);

        // 4. Seeder de Compras (Entrada de Estoque)
        $compra1 = Compra::create([
            'data_compra' => now()->subDays(5),
            'fornecedor_id' => $fornecedor1->id,
            'subtotal' => 231.00,
            'valor_desconto' => 11.00,
            'tipo_desconto' => 'reais',
            'total' => 220.00
        ]);

        // Inserindo os itens da compra via DB::table (protege caso não tenha criado os models de Itens ainda)
        DB::table('itens_compra')->insert([
            ['compra_id' => $compra1->id, 'produto_id' => $p1->id, 'quantidade' => 2, 'custo_unitario' => 55.50, 'subtotal' => 111.00, 'created_at' => now(), 'updated_at' => now()],
            ['compra_id' => $compra1->id, 'produto_id' => $p4->id, 'quantidade' => 2, 'custo_unitario' => 60.00, 'subtotal' => 120.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Seeder de Vendas (Saída de Estoque)
        $venda1 = Venda::create([
            'user_id' => $user->id,
            'cliente_nome' => 'JOÃO DA SILVA',
            'cliente_telefone' => '(19) 98888-7777',
            'cliente_cpf_cnpj' => '123.456.789-00',
            'subtotal' => 200.00,
            'valor_desconto' => 10,
            'tipo_desconto' => 'porcentagem', // 10% de desconto
            'total' => 180.00,
            'status' => 'concluido',
            'observacoes' => 'Cliente pediu revisão rápida para viagem.',
            'data_venda' => now()->subDays(2)
        ]);

        DB::table('itens_venda')->insert([
            ['venda_id' => $venda1->id, 'produto_id' => $p2->id, 'quantidade' => 4, 'preco_unitario' => 45.00, 'subtotal' => 180.00, 'created_at' => now(), 'updated_at' => now()],
            ['venda_id' => $venda1->id, 'produto_id' => $p3->id, 'quantidade' => 1, 'preco_unitario' => 20.00, 'subtotal' => 20.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Mensagem de sucesso no terminal
        $this->command->info('Seeder executado com sucesso! Peças, Fornecedores, Compras e Vendas foram inseridos no banco.');

        $this->call([
        ProdutoSeeder::class,
        ]);
    }
}       
