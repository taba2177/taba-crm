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
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // This assumes you have a `services` table. If not, you can make this nullable or remove it.
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();

            // Moyasar Specific Fields
            $table->string('moyasar_payment_id')->unique()->comment('The unique ID from Moyasar');
            $table->string('status')->index()->comment('e.g., paid, failed, authorized');
            $table->unsignedInteger('amount')->comment('Amount in the smallest currency unit (e.g., halalas)');
            $table->string('currency', 3)->default('SAR');
            $table->string('payment_method')->nullable()->comment('e.g., creditcard, applepay, stcpay');
            $table->string('description')->nullable();
            $table->unsignedInteger('fee')->default(0)->comment('Transaction fee in halalas');

            // Additional useful fields
            $table->json('metadata')->nullable()->comment('Any extra data from Moyasar');
            $table->timestamp('refunded_at')->nullable();
            $table->unsignedInteger('refunded_amount')->nullable()->comment('Refunded amount in halalas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};