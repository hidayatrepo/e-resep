<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            
            // Informasi Rumah Sakit
            $table->string('hospital_name')->default('RS Delta Surya');
            $table->string('hospital_address')->default('Jl. Sudirman No. 123, Jakarta');
            $table->string('hospital_phone')->default('+62 21 123 4567');
            $table->string('hospital_email')->default('info@rsdeltasurya.com');
            
            // Integrasi API
            $table->string('api_email')->default('hidayathack@gmail.com');
            $table->string('api_password')->default('087856420950');
            $table->string('api_base_url')->default('http://recruitment.rsdeltasurya.com/api/v1');
            
            // Timestamps
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('app_settings')->insert([
            [
                'hospital_name' => 'RS Delta Surya',
                'hospital_address' => 'Jl. Sudirman No. 123, Jakarta',
                'hospital_phone' => '+62 21 123 4567',
                'hospital_email' => 'info@rsdeltasurya.com',
                'api_email' => 'hidayathack@gmail.com',
                'api_password' => '087856420950',
                'api_base_url' => 'http://recruitment.rsdeltasurya.com/api/v1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};