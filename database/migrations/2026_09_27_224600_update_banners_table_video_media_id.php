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
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'mobile_media_id') && !Schema::hasColumn('banners', 'video_media_id')) {
                $table->renameColumn('mobile_media_id', 'video_media_id');
            } elseif (!Schema::hasColumn('banners', 'video_media_id')) {
                $table->foreignId('video_media_id')->nullable()->constrained('media')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'video_media_id') && !Schema::hasColumn('banners', 'mobile_media_id')) {
                $table->renameColumn('video_media_id', 'mobile_media_id');
            }
        });
    }
};
