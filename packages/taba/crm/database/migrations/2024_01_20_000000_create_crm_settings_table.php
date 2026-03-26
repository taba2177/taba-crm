<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, number, json
            $table->string('group')->default('general'); // general, contact, business, seo
            $table->json('label')->nullable(); // Translatable label
            $table->json('description')->nullable(); // Translatable description
            $table->boolean('is_translatable')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_settings');
    }
};
