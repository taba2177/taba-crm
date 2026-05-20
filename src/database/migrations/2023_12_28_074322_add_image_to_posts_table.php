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
        if (Schema::hasColumn('posts', 'image_id')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasTable('media')) {
                $table
                    ->foreignId('image_id')
                    ->after('content')
                    ->nullable()
                    ->constrained('media')
                    ->nullOnDelete();

                return;
            }

            if (Schema::hasTable('curator')) {
                $table->uuid('image_id')->after('content')->nullable();
                $table
                    ->foreign('image_id')
                    ->references('id')
                    ->on('curator')
                    ->nullOnDelete();

                return;
            }

            // Keep migration flow unblocked even if the media table is not installed yet.
            $table->string('image_id')->after('content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'image_id')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            try {
                $table->dropForeign(['image_id']);
            } catch (\Throwable $e) {
                // Ignore if the column has no FK constraint in this environment.
            }

            $table->dropColumn('image_id');
        });
    }
};
