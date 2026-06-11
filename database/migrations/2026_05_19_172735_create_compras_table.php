<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->date('data_compra');
            $table->foreignId('fornecedor_id')->constrained('fornecedores');
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('valor_desconto', 10, 2)->default(0);
            $table->string('tipo_desconto')->default('reais'); // 'reais' ou 'porcentagem'
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
