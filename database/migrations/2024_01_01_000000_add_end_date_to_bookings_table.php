<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Booking;

return new class extends Migration
{
    /**
     * Tambah kolom end_date ke tabel bookings.
     *
     * Kolom ini menyimpan tanggal berakhir sewa secara eksplisit,
     * sehingga kalkulasi perpanjangan berikutnya selalu akurat
     * tanpa perlu menghitung ulang dari check_in_date + duration_months.
     *
     * Setelah migrasi berjalan, kolom langsung diisi dari data existing.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('duration_months');
        });

        Booking::whereNull('end_date')
            ->whereNotNull('check_in_date')
            ->whereNotNull('duration_months')
            ->each(function ($booking) {
                $booking->update([
                    'end_date' => \Carbon\Carbon::parse($booking->check_in_date)
                        ->addMonths($booking->duration_months)
                        ->toDateString(),
                ]);
            });
    }

    /**
     * Rollback: hapus kolom end_date.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
};