<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_columns_to_prescriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // Informasi pembayaran
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending')->after('status');
            $table->decimal('payment_amount', 12, 2)->nullable()->after('total_price');
            $table->datetime('payment_date')->nullable()->after('payment_amount');
            $table->string('payment_method')->nullable()->after('payment_date');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->text('payment_notes')->nullable()->after('payment_reference');
            // HAPUS BARIS INI: $table->string('pharmacist_name')->nullable()->after('payment_notes');
            // Karena pharmacist_name sudah ada di migration awal
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_amount',
                'payment_date',
                'payment_method',
                'payment_reference',
                'payment_notes'
                // JANGAN drop pharmacist_name karena sudah ada dari awal
            ]);
        });
    }
};