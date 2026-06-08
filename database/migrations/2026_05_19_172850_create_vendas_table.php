<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vendas', function (Blueprint $table) {
        $table->id(); // PK
        
        // Relacionamento com quem fez a venda (RF06)
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        
        // Dados do Cliente (Vindo do seu formulário do Frontend)
        $table->string('cliente_nome')->nullable();
        $table->string('cliente_telefone')->nullable();
        $table->string('cliente_email')->nullable();
        $table->string('cliente_cpf_cnpj')->nullable();
        $table->string('cliente_rg_ie')->nullable();
        $table->string('cliente_endereco')->nullable();
        $table->string('cliente_bairro')->nullable();
        $table->string('cliente_cidade')->nullable();
        $table->string('cliente_estado', 2)->nullable();
        $table->string('cliente_cep')->nullable();

        // Valores e Descontos (RF08)
        $table->decimal('subtotal', 10, 2);
        $table->decimal('valor_desconto', 10, 2)->default(0);
        $table->string('tipo_desconto')->default('reais'); // 'reais' ou 'porcentagem'
        $table->decimal('total', 10, 2);

        // Controle de Status e Histórico
        $table->dateTime('data_venda')->useCurrent();
        $table->string('status')->default('concluido'); // 'concluido', 'devolvido_parcial', 'devolvido'
        $table->text('observacoes')->nullable();
        
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
