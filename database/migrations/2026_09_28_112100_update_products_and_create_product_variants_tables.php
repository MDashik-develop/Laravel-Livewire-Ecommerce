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
        // 1. Add variant support and base pricing/stock columns to products table
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('name');
            $table->string('sku')->nullable()->after('has_variants');
            $table->decimal('price', 12, 2)->nullable()->after('sku');
            $table->decimal('cost_price', 12, 2)->nullable()->after('price');
            $table->decimal('discount_price', 12, 2)->nullable()->after('cost_price');
            $table->integer('stock')->default(0)->after('discount_price');
            $table->string('barcode')->nullable()->after('stock');
            $table->decimal('weight', 8, 2)->nullable()->after('barcode');
        });

        // 2. Product Variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('is_default')->default(false); // Indicates default variant for single products
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Product Variant Attributes (pivot between variant and attribute values)
        Schema::create('product_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete(); // attribute/color level image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attributes');
        Schema::dropIfExists('product_variants');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'has_variants',
                'sku',
                'price',
                'cost_price',
                'discount_price',
                'stock',
                'barcode',
                'weight',
            ]);
        });
    }
};
