<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_id');   // owner who added
            $table->string('name');
            $table->string('location');               // FIXED - you need this
            $table->string('size');
            $table->string('contact');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('property_doc')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();

            // Foreign key
            $table->foreign('owner_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
