<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Devolucao;
use App\Models\ItemDevolucao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendaController extends Controller
{
    public function exibirPagina()
    {
        return view('vendas.index'); 
    }
    public function index()
    {
        $vendas = Venda::with('itens.produto')->orderBy('id', 'desc')->get();
        return response()->json($vendas);
    }

    // Salva a venda e dá baixa no estoque (RF02)
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Cria a Venda principal
            $venda = Venda::create([
                'user_id' => Auth::id() ?? null, // Aceita null se não estiver logado
                'cliente_nome' => $request->cliente['nome'] ?? $request->buscaCliente,
                'cliente_telefone' => $request->cliente['telefone'] ?? null,
                'cliente_cpf_cnpj' => $request->cliente['cpf_cnpj'] ?? null,
                'subtotal' => $request->subtotal,
                'valor_desconto' => $request->valorDesconto ?: 0,
                'tipo_desconto' => $request->tipoDesconto,
                'total' => $request->totalFinal,
                'status' => 'concluido'
            ]);

            // 2. Cria os Itens e baixa o estoque
            foreach ($request->itens as $item) {
                if (!empty($item['produto_id'])) {
                    ItemVenda::create([
                        'venda_id' => $venda->id,
                        'produto_id' => $item['produto_id'],
                        'quantidade' => $item['qtd'],
                        'preco_unitario' => $item['valor'],
                        'subtotal' => $item['qtd'] * $item['valor']
                    ]);

                    // RF02 - Dá baixa no estoque
                    $produto = Produto::find($item['produto_id']);
                    if($produto) {
                        $produto->decrement('estoque', $item['qtd']);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Venda salva!', 'id' => $venda->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Registra a devolução e devolve ao estoque (RF10)
    public function devolver($id)
    {
        DB::beginTransaction();
        try {
            $venda = Venda::with('itens')->findOrFail($id);
            
            if ($venda->status === 'devolvido') {
                return response()->json(['error' => 'Venda já foi devolvida.'], 400);
            }

            // 1. Registra na tabela de devoluções
            $devolucao = Devolucao::create([
                'venda_id' => $venda->id,
                'user_id' => 1, // Mudar para Auth::id() depois
                'valor_estornado' => $venda->total,
                'motivo_devolucao' => 'Devolução total registrada pelo sistema'
            ]);

            // 2. Devolve os itens pro estoque e registra em itens_devolucao
            foreach ($venda->itens as $item) {
                ItemDevolucao::create([
                    'devolucao_id' => $devolucao->id,
                    'produto_id' => $item->produto_id,
                    'quantidade_devolvida' => $item->quantidade
                ]);

                // Retorna ao estoque (RF10)
                $produto = Produto::find($item->produto_id);
                if($produto) {
                    $produto->increment('quantidade', $item->quantidade);
                }
            }

            // 3. Atualiza o status da Venda
            $venda->update(['status' => 'devolvido']);

            DB::commit();
            return response()->json(['message' => 'Devolução registrada e estoque atualizado!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}