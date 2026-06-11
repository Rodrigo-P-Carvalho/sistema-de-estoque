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
    // 2. Salva a compra vinda do JavaScript (Apenas para produtos existentes)
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validação de segurança para garantir que o fornecedor foi selecionado
            if (!$request->input('fornecedor_id')) {
                throw new \Exception("Por favor, selecione um fornecedor válido da lista.");
            }

            // Cria a compra principal
            $compra = Compra::create([
                'fornecedor_id'  => $request->input('fornecedor_id'),
                'data_compra'    => now(),
                'subtotal'       => $request->input('subtotal'),
                'valor_desconto' => $request->input('valorDesconto') ?: 0,
                'tipo_desconto'  => $request->input('tipoDesconto') ?? 'reais',
                'total'          => $request->input('totalFinal'),
            ]);

            foreach ($request->itens as $item) {
                $produtoId = $item['produto_id'];

                // BARRAGEM DE SEGURANÇA: Se não tem ID, bloqueia a gravação!
                if (empty($produtoId)) {
                    throw new \Exception("O produto '" . ($item['nome'] ?? 'Não informado') . "' não está cadastrado no sistema. Cadastre-o na aba de Produtos antes de lançar esta compra.");
                }

                $quantidade = $item['qtd'] ?? $item['quantidade'] ?? 1;
                $custoUnitario = $item['valor'] ?? $item['custo_unitario'] ?? $item['preco'] ?? 0;

                // O produto obrigatoriamente já existe, então apenas buscamos e incrementamos o estoque
                $produto = Produto::find($produtoId);
                if ($produto) {
                    $produto->increment('estoque', $quantidade);
                    $produto->update(['preco_custo' => $custoUnitario]);
                } else {
                    throw new \Exception("O produto com ID {$produtoId} não foi encontrado na base de dados.");
                }

                // Cria o item da compra vinculado
                ItemCompra::create([
                    'compra_id'      => $compra->id,
                    'produto_id'     => $produtoId,
                    'quantidade'     => $quantidade,
                    'custo_unitario' => $custoUnitario,
                    'subtotal'       => $quantidade * $custoUnitario,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'compra_id' => $compra->id,
                'redirecionar_para' => null // Sem redirecionamento, pois não há novos produtos
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