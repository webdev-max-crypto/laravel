<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount',10,2)->default(0);
            $table->enum('status',['escrow','released'])->default('escrow');
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
        });
    }
    public function down() { Schema::dropIfExists('payments'); }
};
