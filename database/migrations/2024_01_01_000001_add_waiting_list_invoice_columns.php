<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ─────────────────────────────────────────────────────────────────
//  Migration: tambah kolom untuk menghubungkan Invoice ↔ WaitingList
//
//  Jalankan: php artisan migrate
// ─────────────────────────────────────────────────────────────────

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tabel invoices ─────────────────────────────────────
        Schema::table('invoices', function (Blueprint $table) {

            // Relasi ke waiting_lists
            if (!Schema::hasColumn('invoices', 'waiting_list_id')) {
                $table->foreignId('waiting_list_id')
                    ->nullable()
                    ->after('room_id')
                    ->constrained('waiting_lists')
                    ->nullOnDelete();
            }

            // Jenis invoice:
            //   monthly             → tagihan bulanan biasa
            //   waiting_list_dp     → DP 50% saat approve WL
            //   waiting_list_remaining → sisa 50% saat kamar tersedia
            if (!Schema::hasColumn('invoices', 'invoice_type')) {
                $table->string('invoice_type')->default('monthly')->after('waiting_list_id');
            }

            // Flag: invoice sisa WL sudah lunas, admin perlu klik "Aktifkan"
            if (!Schema::hasColumn('invoices', 'pending_tenant_activation')) {
                $table->boolean('pending_tenant_activation')->default(false)->after('status');
            }
        });

        // ── 2. Tabel waiting_lists ────────────────────────────────
        Schema::table('waiting_lists', function (Blueprint $table) {

            // Reference ke Invoice DP (dibuat saat admin approve)
            if (!Schema::hasColumn('waiting_lists', 'dp_invoice_id')) {
                $table->foreignId('dp_invoice_id')
                    ->nullable()
                    ->after('dp_paid_at')
                    ->constrained('invoices')
                    ->nullOnDelete();
            }

            // Reference ke Invoice Sisa (dibuat saat admin klik "Kamar Tersedia")
            if (!Schema::hasColumn('waiting_lists', 'remaining_invoice_id')) {
                $table->foreignId('remaining_invoice_id')
                    ->nullable()
                    ->after('dp_invoice_id')
                    ->constrained('invoices')
                    ->nullOnDelete();
            }

            // Tanggal dikonvert menjadi penghuni aktif
            if (!Schema::hasColumn('waiting_lists', 'converted_at')) {
                $table->timestamp('converted_at')->nullable()->after('notified_at');
            }

            // Catatan admin saat approve
            if (!Schema::hasColumn('waiting_lists', 'notes')) {
                $table->text('notes')->nullable()->after('cancellation_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('waiting_list_id');
            $table->dropColumn(['invoice_type', 'pending_tenant_activation']);
        });

        Schema::table('waiting_lists', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dp_invoice_id');
            $table->dropConstrainedForeignId('remaining_invoice_id');
            $table->dropColumn(['converted_at', 'notes']);
        });
    }
};