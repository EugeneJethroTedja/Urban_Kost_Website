<?php
// database/migrations/2025_xx_xx_add_payment_method_to_renewals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->enum('payment_method', ['fullpay', 'monthly'])
                  ->default('fullpay')
                  ->after('status')
                  ->comment('Metode bayar yang dipilih tenant saat submit renewal');

            $table->unsignedTinyInteger('monthly_invoice_count')
                  ->default(0)
                  ->after('payment_method')
                  ->comment('Berapa invoice bulanan sudah dibuat untuk renewal ini');
        });
    }

    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'monthly_invoice_count']);
        });
    }
};