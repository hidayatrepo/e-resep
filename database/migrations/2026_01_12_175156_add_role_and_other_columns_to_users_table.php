<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('email');
            $table->enum('role', ['doctor', 'pharmacist', 'admin'])->default('doctor')->after('username');
            $table->string('phone')->nullable()->after('role');
            $table->string('specialization')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('specialization');
        });

        // Tambah 3 user: dokter, apoteker, admin
        DB::table('users')->insert([
            [
                'name' => 'Dr. Tirta',
                'email' => 'dokter@eresep.com',
                'username' => 'dokter',
                'password' => Hash::make('password123'),
                'role' => 'doctor',
                'phone' => '081234567891',
                'specialization' => 'Dokter Umum',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apoteker Widya',
                'email' => 'apoteker@eresep.com',
                'username' => 'apoteker',
                'password' => Hash::make('password123'),
                'role' => 'pharmacist',
                'phone' => '081234567892',
                'specialization' => 'Apoteker',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@eresep.com',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567893',
                'specialization' => 'Administrator Sistem',
                'is_active' => true,
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'phone', 'specialization', 'is_active']);
        });
    }
};