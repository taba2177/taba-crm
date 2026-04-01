<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('post_categories', 'is_active')) {
            Schema::table('post_categories', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('section_component');
            });
        }

        if (! Schema::hasColumn('contact_entries', 'is_read')) {
            Schema::table('contact_entries', function (Blueprint $table) {
                $table->boolean('is_read')->default(false)->after('message');
            });
        }
    }

    public function down(): void
    {
        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('contact_entries', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};
