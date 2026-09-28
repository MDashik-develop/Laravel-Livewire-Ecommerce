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
        // 1. Product Galleries (Multiple images for a product)
        if (!Schema::hasTable('product_galleries')) {
            Schema::create('product_galleries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Stock Logs (Stock activity history)
        if (!Schema::hasTable('stock_logs')) {
            Schema::create('stock_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('type')->default('manual_adjustment'); // initial, manual_adjustment, sale, return, restock, damage
                $table->integer('quantity_change'); // e.g. +10, -2
                $table->integer('old_stock');
                $table->integer('new_stock');
                $table->string('reason')->nullable();
                $table->string('reference_type')->nullable(); // e.g. App\Models\Order
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->timestamps();
            });
        }

        // 3. Add product_variant_id to order_items and carts for seamless ordering of variants
        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'product_variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }

        if (Schema::hasTable('carts') && !Schema::hasColumn('carts', 'product_variant_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('carts') && Schema::hasColumn('carts', 'product_variant_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropConstrainedForeignId('product_variant_id');
            });
        }

        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'product_variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropConstrainedForeignId('product_variant_id');
            });
        }

        Schema::dropIfExists('stock_logs');
        Schema::dropIfExists('product_galleries');
    }
};
