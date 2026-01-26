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
        Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('customer'); // admin, owner, customer
        $table->string('phone')->nullable();
        $table->string('cnic')->nullable();
        $table->string('cnic_front')->nullable();
        $table->string('cnic_back')->nullable();
        $table->string('property_document')->nullable();
        $table->string('profile_photo')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role','phone','cnic','cnic_front','cnic_back','property_document','profile_photo']);
    });
    }
};
