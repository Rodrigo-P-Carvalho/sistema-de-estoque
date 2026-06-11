<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\Compra;
use App\Models\Venda;
use App\Models\Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Iniciando a geração de dados em massa para a apresentação...');

        // ==========================================
        // 1. PERFIL E USUÁRIO ADMIN
        // ==========================================
        $perfilAdmin = Perfil::firstOrCreate(
            ['id' => 1],
            ['descricao' => 'Administrador', 'permissoes' => ['cadastrar_usuarios', 'compras', 'vendas', 'produtos', 'administracao']]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'id' => 1,
                'name' => 'Administrador Geral',
                'password' => Hash::make('admin123'),
                'perfil_id' => $perfilAdmin->id,
            ]
        );

        // ==========================================
        // 2. FORNECEDORES (Gerando 10 aleatórios)
        // ==========================================
        $fornecedoresIds = [];
        for ($i = 0; $i < 10; $i++) {
            $fornecedor = Fornecedor::create([
                'razao_social' => $faker->company . ' Peças e Acessórios S.A.',
                'nome_fantasia' => $faker->company,
                'cnpj' => $faker->cnpj,
                'telefone' => $faker->cellphoneNumber,
                'email' => $faker->companyEmail,
            ]);
            $fornecedoresIds[] = $fornecedor->id;
        }
        $this->command->info('10 Fornecedores criados.');

        // ==========================================
        // 3. VEÍCULOS REAIS (Gerando 20 comuns no Brasil)
        // ==========================================
        $marcasModelos = [
            'Volkswagen' => ['Gol', 'Polo', 'Saveiro', 'Virtus', 'Fox'],
            'Chevrolet' => ['Onix', 'Prisma', 'S10', 'Cruze', 'Tracker'],
            'Fiat' => ['Strada', 'Argo', 'Toro', 'Mobi', 'Palio'],
            'Toyota' => ['Corolla', 'Hilux', 'Yaris', 'Etios'],
        ];

        $veiculosIds = [];
        foreach ($marcasModelos as $marca => $modelos) {
            foreach ($modelos as $modelo) {
                $id = DB::table('veiculos')->insertGetId([
                    'marca' => $marca,
                    'modelo' => $modelo,
                    'ano' => $faker->numberBetween(2010, 2026),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $veiculosIds[] = $id;
            }
        }
        $this->command->info('20 Veículos criados.');

        // ==========================================
        // 4. PRODUTOS (Gerando 50 peças de carro)
        // ==========================================
        $pecas = ['Pastilha de Freio', 'Amortecedor', 'Correia Dentada', 'Bateria 60Ah', 'Óleo 5W30', 'Filtro de Ar', 'Vela de Ignição', 'Bomba D\'Água', 'Disco de Freio', 'Radiador', 'Kit Embreagem', 'Pneu 175/70 R14', 'Farol Dianteiro', 'Palheta Limpador', 'Sonda Lambda'];
        $marcasPecas = ['Bosch', 'Moura', 'Castrol', 'Cobreq', 'NGK', 'Cofap', 'Valeo', 'Wega', 'Pirelli', 'Magneti Marelli'];

        $produtosIds = [];
        for ($i = 0; $i < 50; $i++) {
            $produto = Produto::create([
                'nome' => $faker->randomElement($pecas) . ' ' . $faker->word,
                'marca' => $faker->randomElement($marcasPecas),
                'descricao' => $faker->sentence,
                'preco' => $faker->randomFloat(2, 20, 800),
                'estoque' => $faker->numberBetween(5, 100),
                'quantidade_minima' => $faker->numberBetween(2, 10),
                'codigo_interno' => strtoupper($faker->bothify('PEC-####')),
                'codigo_barras' => $faker->ean13,
                'localizacao' => 'Corredor ' . $faker->randomLetter . ' - Prat. ' . $faker->numberBetween(1, 10)
            ]);
            $produtosIds[] = $produto->id;

            // Vincula de 1 a 3 veículos compatíveis com essa peça
            $veiculosCompativeis = $faker->randomElements($veiculosIds, $faker->numberBetween(1, 3));
            foreach ($veiculosCompativeis as $vId) {
                DB::table('produto_veiculo')->insert(['produto_id' => $produto->id, 'veiculo_id' => $vId]);
            }
        }
        $this->command->info('50 Produtos criados e vinculados a veículos.');

        // ==========================================
        // 5. COMPRAS (Entradas de Estoque - 30 Registros)
        // ==========================================
        for ($i = 0; $i < 30; $i++) {
            $totalCompra = 0;
            $dataCompra = $faker->dateTimeBetween('-6 months', 'now');
            
            $compra = Compra::create([
                'data_compra' => $dataCompra,
                'fornecedor_id' => $faker->randomElement($fornecedoresIds),
                'subtotal' => 0, // Será atualizado logo abaixo
                'valor_desconto' => $faker->randomFloat(2, 0, 50),
                'tipo_desconto' => 'reais',
                'total' => 0
            ]);

            // Adiciona de 1 a 5 itens nesta compra
            $qtdItens = $faker->numberBetween(1, 5);
            $produtosSorteados = $faker->randomElements($produtosIds, $qtdItens);

            foreach ($produtosSorteados as $pId) {
                $qtd = $faker->numberBetween(5, 30);
                $custo = $faker->randomFloat(2, 10, 300);
                $subtotalItem = $qtd * $custo;
                $totalCompra += $subtotalItem;

                DB::table('itens_compra')->insert([
                    'compra_id' => $compra->id,
                    'produto_id' => $pId,
                    'quantidade' => $qtd,
                    'custo_unitario' => $custo,
                    'subtotal' => $subtotalItem,
                    'created_at' => $dataCompra,
                    'updated_at' => $dataCompra,
                ]);
            }

            // Atualiza o total da compra
            $compra->update([
                'subtotal' => $totalCompra,
                'total' => $totalCompra - $compra->valor_desconto
            ]);
        }
        $this->command->info('30 Notas de Compra geradas.');

        // ==========================================
        // 6. VENDAS E PDV (Saídas de Estoque - 80 Registros)
        // ==========================================
        for ($i = 0; $i < 80; $i++) {
            $totalVenda = 0;
            $dataVenda = $faker->dateTimeBetween('-3 months', 'now');
            
            // 10% de chance de ser uma venda devolvida
            $status = $faker->randomElement(['concluido', 'concluido', 'concluido', 'concluido', 'devolvido']);

            $vendaId = DB::table('vendas')->insertGetId([
                'user_id' => $admin->id,
                'cliente_nome' => $faker->name,
                'cliente_telefone' => $faker->cellphoneNumber,
                'cliente_cpf_cnpj' => $faker->cpf,
                'subtotal' => 0,
                'valor_desconto' => $faker->randomElement([0, 5, 10]), // 0, 5 ou 10 de desconto
                'tipo_desconto' => 'porcentagem',
                'total' => 0,
                'status' => $status,
                'observacoes' => $faker->optional(0.3)->sentence, // 30% de chance de ter observação
                'data_venda' => $dataVenda,
                'created_at' => $dataVenda,
                'updated_at' => $dataVenda,
            ]);

            // Adiciona de 1 a 4 itens nesta venda
            $qtdItens = $faker->numberBetween(1, 4);
            $produtosSorteados = $faker->randomElements($produtosIds, $qtdItens);

            foreach ($produtosSorteados as $pId) {
                $produtoAtual = Produto::find($pId);
                $qtd = $faker->numberBetween(1, 4);
                $preco = $produtoAtual->preco;
                $subtotalItem = $qtd * $preco;
                $totalVenda += $subtotalItem;

                DB::table('itens_venda')->insert([
                    'venda_id' => $vendaId,
                    'produto_id' => $pId,
                    'quantidade' => $qtd,
                    'preco_unitario' => $preco,
                    'subtotal' => $subtotalItem,
                    'created_at' => $dataVenda,
                    'updated_at' => $dataVenda,
                ]);
            }

            // Calcula os descontos e atualiza a venda final
            $descontoCalculado = ($totalVenda * DB::table('vendas')->where('id', $vendaId)->value('valor_desconto')) / 100;
            
            DB::table('vendas')->where('id', $vendaId)->update([
                'subtotal' => $totalVenda,
                'total' => $totalVenda - $descontoCalculado
            ]);

            // Se for status 'devolvido', cria o registro de devolução
            if ($status === 'devolvido') {
                $devolucaoId = DB::table('devolucoes')->insertGetId([
                    'venda_id' => $vendaId,
                    'user_id' => $admin->id,
                    'data_devolucao' => Carbon::parse($dataVenda)->addDays($faker->numberBetween(1, 5)),
                    'motivo_devolucao' => $faker->sentence,
                    'valor_estornado' => $totalVenda - $descontoCalculado,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Devolve os itens no banco
                foreach ($produtosSorteados as $pId) {
                    DB::table('itens_devolucao')->insert([
                        'devolucao_id' => $devolucaoId,
                        'produto_id' => $pId,
                        'quantidade_devolvida' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        $this->command->info('80 Vendas registradas no PDV (incluindo histórico de devoluções).');

        $this->command->info('✅ Tudo pronto! Banco de dados recheado e preparado para a apresentação!');
    }
}