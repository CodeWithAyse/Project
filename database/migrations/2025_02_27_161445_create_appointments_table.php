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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained()->onDelete('cascade');
            $table->foreignId('polyclinicId')->constrained()->onDelete('cascade');
            $table->foreignId('doctorId')->constrained()->onDelete('cascade');
            $table->date('appointmentDate');
            $table->time('appointmentTime');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['doctorId', 'polyclinicId', 'appointmentDate', 'appointmentTime'], 'unique_appointment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
