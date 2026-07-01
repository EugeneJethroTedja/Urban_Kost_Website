<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            // Simpan layanan tambahan yang dipilih tenant saat pengajuan
            // Format: {"ac":{"label":"AC","price":150000},"wifi":{"label":"Wi-Fi","price":75000}}
            if (!Schema::hasColumn('renewals', 'additional_services')) {
                $table->json('additional_services')->nullable()->after('amount');
            }

            // Total harga layanan tambahan per bulan (untuk referensi invoice)
            if (!Schema::hasColumn('renewals', 'service_amount_monthly')) {
                $table->unsignedInteger('service_amount_monthly')->default(0)->after('additional_services');
            }

            // reviewed_by & reviewed_at mungkin belum ada
            if (!Schema::hasColumn('renewals', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('initiated_by');
            }
            if (!Schema::hasColumn('renewals', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('renewals', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->dropColumnIfExists('additional_services');
            $table->dropColumnIfExists('service_amount_monthly');
        });
    }
};