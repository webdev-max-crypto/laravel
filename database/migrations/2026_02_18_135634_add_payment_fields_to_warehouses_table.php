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
        Schema::table('warehouses', function (Blueprint $table) {
             $table->string('jazzcash_number')->nullable();
        $table->string('stripe_account_id')->nullable();
        $table->enum('preferred_payment_method',['stripe','jazzcash'])
              ->default('stripe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
             $table->dropColumn([
            'jazzcash_number',
            'stripe_account_id',
            'preferred_payment_method'
        ]);
        });
    }
};
