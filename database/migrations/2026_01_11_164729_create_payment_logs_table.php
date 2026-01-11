<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_payment_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->string('action');
            $table->json('details')->nullable();
            $table->string('user_type')->nullable()->comment('doctor, pharmacist, system');
            $table->string('user_name')->nullable();
            $table->timestamps();
            
            $table->index(['prescription_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};