<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_entries', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('service', 255)->nullable()->after('phone');
            $table->string('email', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contact_entries', function (Blueprint $table) {
            $table->dropColumn(['phone', 'service']);
            $table->string('email', 255)->nullable(false)->change();
        });
    }
};
