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
        Schema::create('attribute_definition_product_type', function (Blueprint $table) {
            $table->foreignUuid('attribute_definition_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_type_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['attribute_definition_id', 'product_type_id']);
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignUuid('category_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['category_id', 'product_id']);
        });

        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('attribute_definition_id')->constrained()->restrictOnDelete();
            $table->json('value');
            $table->timestamps();
            $table->unique(['product_id', 'attribute_definition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('attribute_definition_product_type');
    }
};
