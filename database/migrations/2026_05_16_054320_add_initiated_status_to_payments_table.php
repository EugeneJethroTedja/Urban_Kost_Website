<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ════════════════════════════════════════════════════════════════════════
// MIGRATION: Tambah nilai 'initiated' ke kolom status payments
//
// Jalankan dengan: php artisan migrate
//
// PENJELASAN STATUS:
//   'initiated' = Snap popup Midtrans sudah dibuka, user BELUM bayar.
//                 Ini hanya draft snap token — admin tidak perlu melihatnya.
//   'pending'   = Bukti transfer manual sudah diupload, menunggu verifikasi.
//   'verified'  = Pembayaran dikonfirmasi (manual oleh admin atau via webhook).
//   'rejected'  = Pembayaran ditolak / transaksi Midtrans gagal.
// ════════════════════════════════════════════════════════════════════════

return new class extends Migration
{
    public function up(): void
    {
        // Jika kolom status adalah ENUM — jalankan raw SQL
        // Sesuaikan daftar nilai enum dengan yang ada di tabel Anda saat ini
        DB::statement("
            ALTER TABLE payments
            MODIFY COLUMN status
            ENUM('initiated','pending','verified','rejected','cancelled')
            NOT NULL DEFAULT 'pending'
        ");

        // ── Opsional: Bersihkan data 'initiated' lama yang mungkin tersimpan
        // dengan status 'pending' + method 'midtrans' + tanpa transfer_proof
        // (hanya jika Anda yakin data tersebut adalah draft snap token) ──
        //
        // DB::table('payments')
        //     ->where('status', 'pending')
        //     ->where('method', 'midtrans')
        //     ->whereNull('transfer_proof')
        //     ->whereNull('verified_at')
        //     ->update(['status' => 'initiated']);
    }

    public function down(): void
    {
        // Kembalikan ke enum tanpa 'initiated'
        DB::statement("
            ALTER TABLE payments
            MODIFY COLUMN status
            ENUM('pending','verified','rejected','cancelled')
            NOT NULL DEFAULT 'pending'
        ");
    }
};