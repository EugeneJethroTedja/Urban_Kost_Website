<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Layanan tambahan yang dipilih saat booking (disimpan sebagai JSON)
            // Format: {"ac": {"label": "AC", "price": 150000}, ...}
            $table->json('additional_services')->nullable()->after('message');
 
            // Total harga layanan tambahan per bulan (dalam Rupiah)
            $table->unsignedBigInteger('service_amount')->default(0)->after('additional_services');
        });
    }
 
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['additional_services', 'service_amount']);
        });
    }
};
 