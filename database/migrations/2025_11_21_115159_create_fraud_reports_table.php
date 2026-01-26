<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('fraud_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('reported_by');
            $table->text('message')->nullable();
            $table->enum('status',['pending','resolved'])->default('pending');
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down() { Schema::dropIfExists('fraud_reports'); }
};
