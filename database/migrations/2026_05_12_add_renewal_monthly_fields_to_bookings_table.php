<?php
// database/migrations/2025_xx_xx_add_renewal_monthly_fields_to_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Metode pembayaran renewal yang sedang berjalan
            $table->enum('renewal_payment_method', ['fullpay', 'monthly'])
                  ->nullable()
                  ->after('payment_method')
                  ->comment('Diisi saat admin approve renewal bulanan, dikosongkan saat renewal selesai');

            // Sudah berapa invoice bulanan renewal yang dibuat
            $table->unsignedTinyInteger('renewal_monthly_count')
                  ->default(0)
                  ->after('renewal_payment_method')
                  ->comment('Counter invoice bulanan renewal yang sudah diterbitkan');

            // Total bulan yang harus dibuat invoice (= renewal->duration_months)
            $table->unsignedTinyInteger('renewal_duration')
                  ->nullable()
                  ->after('renewal_monthly_count')
                  ->comment('Durasi perpanjangan dalam bulan, disalin dari renewal');

            // FK ke tabel renewals
            $table->unsignedBigInteger('renewal_id')
                  ->nullable()
                  ->after('renewal_duration')
                  ->comment('Renewal yang sedang aktif diproses secara bulanan');

            // Tanggal mulai periode perpanjangan (awal bulan pertama)
            $table->date('renewal_period_start')
                  ->nullable()
                  ->after('renewal_id')
                  ->comment('Tanggal mulai periode perpanjangan, untuk hitung periode bulan ke-N');

            // Tanggal billing tiap bulan (misal: tanggal 1, 15, dst)
            $table->unsignedTinyInteger('renewal_monthly_day')
                  ->nullable()
                  ->after('renewal_period_start')
                  ->comment('Tanggal jatuh tempo billing bulanan');

            // Biaya layanan tambahan per bulan (disalin dari renewal)
            $table->unsignedInteger('renewal_service_monthly')
                  ->default(0)
                  ->after('renewal_monthly_day')
                  ->comment('Biaya layanan tambahan per bulan untuk renewal ini');
        });

        // Foreign key terpisah agar mudah di-drop
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('renewal_id')
                  ->references('id')
                  ->on('renewals')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['renewal_id']);
            $table->dropColumn([
                'renewal_payment_method',
                'renewal_monthly_count',
                'renewal_duration',
                'renewal_id',
                'renewal_period_start',
                'renewal_monthly_day',
                'renewal_service_monthly',
            ]);
        });
    }
};