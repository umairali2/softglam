<?php

use App\ProductVariantStatus;
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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('status')->default(ProductVariantStatus::Active->value);
            $table->unsignedBigInteger('price_amount');
            $table->unsignedBigInteger('compare_at_amount')->nullable();
            $table->char('currency', 3)->default('PKR');
            $table->decimal('weight_g', 10, 3)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->index(['product_id', 'status', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
