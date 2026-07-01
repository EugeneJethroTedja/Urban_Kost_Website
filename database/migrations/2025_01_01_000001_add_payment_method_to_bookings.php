<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Metode pembayaran: lump_sum = bayar lunas, monthly = bayar bulanan
            $table->enum('payment_method', ['lump_sum', 'monthly'])
                ->default('lump_sum')
                ->after('booking_type')
                ->comment('lump_sum = bayar total sekaligus, monthly = bayar per bulan');

            // Tanggal penagihan bulanan (hari dalam sebulan, 1-28)
            // Diisi otomatis dari check_in_date saat booking disetujui
            $table->unsignedTinyInteger('monthly_billing_day')
                ->nullable()
                ->after('payment_method')
                ->comment('Hari penagihan tiap bulan (1-28), diambil dari check_in_date');

            // Jumlah invoice bulanan yang sudah diterbitkan (bulan ke berapa)
            $table->unsignedSmallInteger('monthly_invoice_count')
                ->default(0)
                ->after('monthly_billing_day')
                ->comment('Berapa invoice bulanan sudah diterbitkan untuk booking ini');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'monthly_billing_day', 'monthly_invoice_count']);
        });
    }
};