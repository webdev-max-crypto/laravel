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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->text('sms_content')->nullable()->after('payment_method');
            $table->timestamp('payment_date')->nullable()->after('sms_content');


    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
