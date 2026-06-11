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
        // Busca os produtos trazendo apenas o necessário para ficar rápido
        $produtos = Produto::all(['id', 'nome', 'preco', 'estoque']);

        return view('vendas.index', compact('produtos')); // Ajuste o nome da view se for diferente
    }
    public function index()
    {
        $vendas = Venda::with(['itens.produto'])->orderBy('id', 'desc')->get();
        return response()->json($vendas);
    }
    
    public function store(Request $request)
    {
        // 1. Inicia uma transação para garantir que tudo salve ou nada salve (evita dados órfãos)
        DB::beginTransaction();

        try {
            $dadosCliente = $request->input('cliente');

            // 2. Cria o cabeçalho da Venda
            $venda = Venda::create([
                'user_id'          => Auth::id() ?? 1, // Associa o usuário logado (ou ID 1 se não houver autenticação ainda)
                'cliente_nome'     => $dadosCliente['nome'] ?? $request->input('buscaCliente'),
                'cliente_telefone' => $dadosCliente['telefone'] ?? null,
                'cliente_email'    => $dadosCliente['email'] ?? null,
                'cliente_cpf_cnpj' => $dadosCliente['cpf_cnpj'] ?? null,
                'cliente_rg_ie'    => $dadosCliente['rg_ie'] ?? null,
                'cliente_endereco' => $dadosCliente['endereco'] ?? null,
                'cliente_bairro'   => $dadosCliente['bairro'] ?? null,
                'cliente_cidade'   => $dadosCliente['cidade'] ?? null,
                'cliente_estado'   => $dadosCliente['estado'] ?? null,
                'cliente_cep'      => $dadosCliente['cep'] ?? null,
                
                'subtotal'         => $request->input('subtotal'),
                'valor_desconto'   => $request->input('valorDesconto') ?? 0,
                'tipo_desconto'    => $request->input('tipoDesconto') ?? 'reais',
                'total'            => $request->input('totalFinal'),
                'status'           => 'concluido',
                'observacoes'      => $request->input('observacoes') ?? null,
            ]);

            // 3. Pega o array de itens/produtos vindos da requisição
            $itens = $request->input('itens', []);

            if (empty($itens)) {
                return response()->json(['success' => false, 'error' => 'A venda precisa ter pelo menos um produto.'], 400);
            }

            // 4. Percorre cada produto inserindo na tabela relacionada e baixando o estoque
            foreach ($itens as $item) {
                // Evita processar linhas em branco que possam vir do frontend
                if (empty($item['produto_id'])) continue;

                // Salva na tabela itens_venda (Verifique se no seu model a FK é venda_id)
                ItemVenda::create([
                    'venda_id'        => $venda->id,
                    'produto_id'      => $item['produto_id'],
                    'quantidade'      => $item['qtd'] ?? $item['quantidade'],
                    'preco_unitario'  => $item['valor'] ?? $item['preco'],
                    'subtotal'        => ($item['qtd'] ?? $item['quantidade']) * ($item['valor'] ?? $item['preco']),
                ]);

                // 5. Baixa Automática de Estoque (RF10)
                $produto = Produto::find($item['produto_id']);
                if ($produto) {
                    if ($produto->estoque < ($item['qtd'] ?? $item['quantidade'])) {
                        throw new \Exception("Estoque insuficiente para o produto: {$produto->nome}");
                    }
                    
                    $produto->decrement('estoque', $item['qtd'] ?? $item['quantidade']);
                }
            }

            // Se tudo correu bem, salva em definitivo no banco
            DB::commit();

            return response()->json([
                'id' => $venda->id, 
                'success' => true, 
                'message' => 'Venda realizada e estoque atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            // Se der qualquer erro (falha de banco, falta de estoque, etc), desfaz tudo
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
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