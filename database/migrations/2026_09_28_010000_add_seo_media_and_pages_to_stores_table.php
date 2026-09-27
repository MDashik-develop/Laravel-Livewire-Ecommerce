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
        Schema::table('stores', function (Blueprint $table) {
            // Media additions
            if (!Schema::hasColumn('stores', 'fav_media_id')) {
                $table->foreignId('fav_media_id')->nullable()->after('media_id')->constrained('media')->nullOnDelete();
            }
            if (!Schema::hasColumn('stores', 'og_media_id')) {
                $table->foreignId('og_media_id')->nullable()->after('fav_media_id')->constrained('media')->nullOnDelete();
            }

            // General & Contact additions
            if (!Schema::hasColumn('stores', 'email')) {
                $table->string('email', 255)->nullable()->after('phone');
            }

            // SEO Columns
            if (!Schema::hasColumn('stores', 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->after('address');
            }
            if (!Schema::hasColumn('stores', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('stores', 'meta_keywords')) {
                $table->string('meta_keywords', 500)->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('stores', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('stores', 'sitemap')) {
                $table->string('sitemap', 500)->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('stores', 'twitter_title')) {
                $table->string('twitter_title', 255)->nullable()->after('sitemap');
            }
            if (!Schema::hasColumn('stores', 'twitter_description')) {
                $table->text('twitter_description')->nullable()->after('twitter_title');
            }

            // Marketing & Analytics Tracking IDs
            if (!Schema::hasColumn('stores', 'meta_pixel_id')) {
                $table->string('meta_pixel_id', 100)->nullable()->after('twitter_description');
            }
            if (!Schema::hasColumn('stores', 'google_ads_id')) {
                $table->string('google_ads_id', 100)->nullable()->after('meta_pixel_id');
            }
            if (!Schema::hasColumn('stores', 'google_analytics_id')) {
                $table->string('google_analytics_id', 100)->nullable()->after('google_ads_id');
            }

            // Social links
            if (!Schema::hasColumn('stores', 'facebook_url')) {
                $table->string('facebook_url', 500)->nullable()->after('google_analytics_id');
            }
            if (!Schema::hasColumn('stores', 'instagram_url')) {
                $table->string('instagram_url', 500)->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('stores', 'youtube_url')) {
                $table->string('youtube_url', 500)->nullable()->after('instagram_url');
            }

            // Policy & Content Pages
            if (!Schema::hasColumn('stores', 'about_page')) {
                $table->longText('about_page')->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('stores', 'contact_page')) {
                $table->longText('contact_page')->nullable()->after('about_page');
            }
            if (!Schema::hasColumn('stores', 'privacy_page')) {
                $table->longText('privacy_page')->nullable()->after('contact_page');
            }
            if (!Schema::hasColumn('stores', 'return_page')) {
                $table->longText('return_page')->nullable()->after('privacy_page');
            }
            if (!Schema::hasColumn('stores', 'terms_page')) {
                $table->longText('terms_page')->nullable()->after('return_page');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $columns = [
                'fav_media_id', 'og_media_id', 'email',
                'meta_title', 'meta_description', 'meta_keywords',
                'canonical_url', 'sitemap', 'twitter_title', 'twitter_description',
                'meta_pixel_id', 'google_ads_id', 'google_analytics_id',
                'facebook_url', 'instagram_url', 'youtube_url',
                'about_page', 'contact_page', 'privacy_page', 'return_page', 'terms_page',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('stores', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
