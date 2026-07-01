<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Nomor bulan untuk invoice bulanan (1 = bulan pertama, 2 = bulan kedua, dst)
            // Null untuk invoice non-monthly (lump_sum, dp, dll)
            $table->unsignedSmallInteger('month_number')
                ->nullable()
                ->after('booking_id')
                ->comment('Bulan ke berapa dari durasi sewa (untuk invoice monthly)');

            // Tambah tipe baru ke invoice_type jika belum ada
            // Sesuaikan dengan enum yang ada di tabel Anda.
            // Jika invoice_type adalah string/varchar, langsung tambah saja.
            // Jika enum, jalankan ALTER TABLE manual atau modifikasi enum.
            //
            // Nilai baru yang perlu didukung:
            //   'monthly'       = invoice sewa bulanan (bulan ke-N)
            //   'monthly_first' = invoice bulan pertama untuk booking monthly
            //
            // Jika kolom invoice_type sudah VARCHAR, kolom ini otomatis mendukung.
            // Jika ENUM, uncomment baris berikut dan sesuaikan:
            //
            // DB::statement("ALTER TABLE invoices MODIFY COLUMN invoice_type
            //     ENUM('booking','renewal','waiting_list_dp','waiting_list_remaining',
            //          'manual','monthly','monthly_first')
            //     NOT NULL DEFAULT 'booking'");
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('month_number');
        });
    }
};