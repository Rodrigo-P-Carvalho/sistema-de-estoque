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
        Schema::create('devolucoes', function (Blueprint $table) {
            $table->id();
            
            // Links essenciais
            $table->foreignId('venda_id')->constrained('vendas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quem aceitou a devolução
            
            $table->dateTime('data_devolucao')->useCurrent();
            $table->text('motivo_devolucao')->nullable();
            $table->decimal('valor_estornado', 10, 2); // Valor devolvido ao cliente
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucoes');
    }
};
