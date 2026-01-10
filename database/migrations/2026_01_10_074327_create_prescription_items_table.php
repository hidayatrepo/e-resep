<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_prescription_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke prescriptions
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            
            // Data obat
            $table->string('medicine_id')->nullable()->comment('ID dari API eksternal');
            $table->string('medicine_name');
            $table->string('unit')->nullable()->comment('Satuan: tablet, botol, etc');
            $table->integer('quantity');
            $table->text('instructions')->nullable()->comment('Cara pakai');
            
            // Harga
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};