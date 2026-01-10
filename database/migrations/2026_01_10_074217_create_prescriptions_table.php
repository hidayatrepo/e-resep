<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_prescriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            
            // Nomor resep (unik)
            $table->string('prescription_number')->unique();
            
            // Informasi pasien (disimpan sebagai string tanpa relasi)
            $table->string('patient_name');
            $table->string('patient_phone')->nullable();
            $table->text('patient_address')->nullable();
            
            // Informasi dokter
            $table->string('doctor_name');
            
            // Tanggal dan waktu pemeriksaan
            $table->datetime('examination_date');
            
            // Tanda-tanda vital
            $table->decimal('height', 5, 2)->nullable()->comment('cm');
            $table->decimal('weight', 5, 2)->nullable()->comment('kg');
            $table->integer('systole')->nullable()->comment('mmHg');
            $table->integer('diastole')->nullable()->comment('mmHg');
            $table->integer('heart_rate')->nullable()->comment('bpm');
            $table->integer('respiration_rate')->nullable()->comment('per minute');
            $table->decimal('temperature', 4, 2)->nullable()->comment('°C');
            
            // Hasil pemeriksaan
            $table->text('examination_result')->nullable();
            
            // Status resep
            $table->enum('status', ['draft', 'process', 'completed', 'cancelled'])->default('draft');
            
            // Informasi apoteker (jika sudah dilayani)
            $table->string('pharmacist_name')->nullable();
            $table->datetime('served_at')->nullable();
            
            // Harga total
            $table->decimal('total_price', 12, 2)->default(0);
            
            // Catatan tambahan
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};