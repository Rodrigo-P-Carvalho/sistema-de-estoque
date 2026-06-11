<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\ItemCompra;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    // 1. Exibe a tela injetando Fornecedores e Produtos
    public function index()
    {
        // Pega todos para o AutoComplete
        $fornecedores = Fornecedor::all(['id', 'nome_fantasia', 'razao_social', 'cnpj', 'telefone', 'email']);
        $produtos = Produto::all(['id', 'nome', 'estoque', 'preco']); 

        return view('compras.index', compact('fornecedores', 'produtos'));
    }

    // 2. Salva a compra vinda do JavaScript
    public function store(Request $request)
    {
        // Usamos transação: se der erro no meio, ele desfaz tudo automaticamente
        DB::beginTransaction();
        
        try {
            $compra = Compra::create([
                'data_compra' => now(),
                'fornecedor_id' => $request->fornecedor_id,
                'total' => $request->total,
            ]);

            $idPrimeiroProdutoNovo = null; // Guardará o ID do novo produto para o redirecionamento

            foreach ($request->itens as $item) {
                $produtoId = $item['produto_id'];

                // SE O PRODUTO NÃO EXISTE: CRIAR AGORA
                if (empty($produtoId)) {
                    $novoProduto = Produto::create([
                        'nome' => mb_strtoupper($item['nome']), // Salva padrão caixa alta
                        'preco' => $item['valor'],
                        'estoque' => $item['qtd'],
                        // Preencha campos obrigatórios da sua tabela com defaults se tiver (ex: preco_venda => 0)
                    ]);

                    $produtoId = $novoProduto->id;
                    
                    // Salva o id do primeiro produto novo criado para mandar o usuário para a tela de edição
                    if (!$idPrimeiroProdutoNovo) {
                        $idPrimeiroProdutoNovo = $produtoId;
                    }
                } 
                // SE O PRODUTO JÁ EXISTE: APENAS ATUALIZA O ESTOQUE
                else {
                    $produto = Produto::find($produtoId);
                    $produto->increment('estoque', $item['qtd']);
                    // Opcional: Atualiza o preço de custo do produto para o valor pago nesta compra mais recente
                    $produto->update(['preco_custo' => $item['valor']]);
                }

                // Cria o Vínculo (Item da Compra)
                ItemCompra::create([
                    'compra_id' => $compra->id,
                    'produto_id' => $produtoId,
                    'quantidade' => $item['qtd'],
                    'custo_unitario' => $item['valor'],
                    'subtotal' => $item['qtd'] * $item['valor'],
                ]);
            }

            DB::commit(); // Salva tudo de vez no banco

            // Resposta de Sucesso para o Javascript
            return response()->json([
                'success' => true,
                'compra_id' => $compra->id,
                // Aqui geramos a URL dinâmica para a tela de edição do seu sistema de produtos!
                'redirecionar_para' => $idPrimeiroProdutoNovo ? route('produtos.index') . "?editar=" . $idPrimeiroProdutoNovo : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function listarAPI() {
        $compras = Compra::with(['fornecedor', 'itens.produto'])->orderBy('id', 'desc')->get();
        
        return response()->json($compras);
    }
}