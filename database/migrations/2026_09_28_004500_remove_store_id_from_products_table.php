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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'store_id')) {
                try {
                    $table->dropConstrainedForeignId('store_id');
                } catch (\Throwable $e) {
                    $table->dropColumn('store_id');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->onDelete('cascade');
            }
        });
    }
};
