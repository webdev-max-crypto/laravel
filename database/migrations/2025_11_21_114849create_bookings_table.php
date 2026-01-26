<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('total_price',10,2)->nullable();
            $table->enum('status',['active','expired','cancelled'])->default('active');
            $table->timestamps();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down() { Schema::dropIfExists('bookings'); }
};
