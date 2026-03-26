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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique()->index();
            $table->json('meta_title',60)->nullable();
            $table->json('meta_description',160)->nullable();
            $table->json('metadata')->nullable();
            $table->json('images')->nullable();
            $table->json('content')->nullable();

            $table->string('homepage_section_component')->nullable();
            $table->json('homepage_section_content')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('post_category_id')
            ->constrained('post_categories')
            ->restrictOnDelete();
            $table->unique(['slug', 'post_category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }



};
