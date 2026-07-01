<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Alter bookings: tambah status 'waiting_list' & kolom baru ──
        // MySQL enum tidak bisa di-extend langsung via ->change(), harus raw SQL
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN status ENUM(
                'pending',
                'under_review',
                'approved',
                'waiting_list',
                'rejected',
                'cancelled',
                'completed'
            ) NOT NULL DEFAULT 'pending'
        ");

        Schema::table('bookings', function (Blueprint $table) {
            // Alasan pembatalan (digunakan saat cancel waiting list)
            $table->string('cancellation_reason')->nullable()->after('message');
        });

        // ── 2. Tabel waiting_lists ─────────────────────────────────────────
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();

            // Relasi ke booking asal (advance booking yang sudah di-acc admin)
            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();

            // Relasi ke kamar yang ditunggu
            $table->foreignId('room_id')
                  ->constrained('rooms')
                  ->cascadeOnDelete();

            // Relasi ke calon penghuni
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Posisi antrean (saat ini selalu 1 karena max 1 per kamar,
            // tapi disimpan untuk kebutuhan histori & extensibility)
            $table->unsignedTinyInteger('position')->default(1);

            // Status waiting list
            // waiting   → sudah acc admin, menunggu kamar kosong
            // notified  → sudah dinotifikasi bahwa kamar tersedia
            // converted → calon penghuni lunasi sisa & resmi jadi penghuni
            // cancelled → dibatalkan oleh calon penghuni
            // expired   → tidak ada respons setelah dinotifikasi (future use)
            $table->enum('status', ['waiting', 'notified', 'converted', 'cancelled', 'expired'])
                  ->default('waiting');

            // ── Informasi DP ──────────────────────────────────────────────
            // DP dibayar setelah masuk waiting list (konfirmasi komitmen)
            $table->decimal('dp_amount', 12, 2)->nullable()
                  ->comment('Nominal DP yang harus/sudah dibayar');
            $table->decimal('dp_paid', 12, 2)->nullable()
                  ->comment('Nominal DP yang sudah terbayar via Midtrans');
            $table->timestamp('dp_paid_at')->nullable();
            $table->string('midtrans_dp_order_id', 100)->nullable();

            // ── Denda pembatalan ──────────────────────────────────────────
            // 10% dari dp_paid jika cancel setelah DP lunas
            $table->decimal('penalty_amount', 12, 2)->nullable()
                  ->comment('Denda 10% dari DP jika cancel');
            $table->boolean('penalty_waived')->default(false)
                  ->comment('Admin bisa waive denda jika ada alasan khusus');

            // ── Timestamps penting ────────────────────────────────────────
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Admin yang menyetujui masuk waiting list');
            $table->timestamp('approved_at')->nullable();

            // Kapan notifikasi "kamar tersedia" dikirim
            $table->timestamp('notified_at')->nullable();

            // Kapan calon penghuni cancel
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason', 500)->nullable();

            // Kapan converted jadi penghuni (setelah full payment)
            $table->timestamp('converted_at')->nullable();

            $table->timestamps();

            // ── Indexes ───────────────────────────────────────────────────
            // Satu kamar hanya boleh punya 1 waiting list aktif (waiting/notified)
            // Ini di-enforce di application level + query, bukan unique constraint
            // karena status bisa berubah (historical records tetap ada)
            $table->index(['room_id', 'status'], 'idx_room_status');
            $table->index(['user_id', 'status'],  'idx_user_status');
            $table->index('booking_id',            'idx_booking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('cancellation_reason');
        });

        // Kembalikan enum status ke nilai semula
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN status ENUM(
                'pending',
                'under_review',
                'approved',
                'rejected',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};