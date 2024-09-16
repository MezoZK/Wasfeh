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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->string('name');
            $table->string('patient_condition');
            $table->datetime('start_date')->nullable();
            $table->integer('dosage');
            $table->datetime('last_taken')->nullable();
            $table->datetime('next_remainder')->nullable();
            $table->string('instructions')->nullable()->default(null);
            $table->integer('quantity');
            $table->integer('quantity_taken')->default(0);
            $table->timestamps();

            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
