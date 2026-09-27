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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('path'); // file name e.g. banner_a1.webp or video_1.mp4
            $table->string('folder')->default('default')->index();
            $table->string('type')->default('image')->index(); // 'image', 'video', 'gif'
            $table->string('extension', 20)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable(); // in bytes
            $table->json('sizes')->nullable(); // sizes in KB e.g. {"original": "150 KB", "large": "80 KB", ...}
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
