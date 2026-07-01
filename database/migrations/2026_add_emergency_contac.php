<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Nomor darurat yang akan dihubungi jika terjadi sesuatu dengan penghuni
            $table->string('emergency_contact', 50)->nullable()->after('identity_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('emergency_contact');
        });
    }
};