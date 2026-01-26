<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('warehouses', function (Blueprint $table) {
        if (!Schema::hasColumn('warehouses', 'location')) {
            $table->text('location')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'size')) {
            $table->string('size')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'contact')) {
            $table->string('contact')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'description')) {
            $table->text('description')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'image')) {
            $table->string('image')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'property_doc')) {
            $table->string('property_doc')->nullable();
        }
        if (!Schema::hasColumn('warehouses', 'status')) {
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
        }
    });
}

    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('warehouses', function (Blueprint $table) {
        //
    });
}
};
