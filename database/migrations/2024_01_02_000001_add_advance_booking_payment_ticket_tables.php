<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tambah kolom ke bookings ───────────────────────────
        Schema::table('bookings', function (Blueprint $table) {
            // Tipe booking: regular (kamar kosong) atau advance (kamar masih terisi)
            $table->enum('booking_type', ['regular', 'advance'])->default('regular')->after('status');
            // Jika advance booking, simpan referensi ke penghuni aktif yang sedang menempati
            $table->unsignedBigInteger('advance_for_user_id')->nullable()->after('booking_type')
                ->comment('User ID penghuni aktif yang sedang menempati kamar saat advance booking masuk');
            // Status renewal penghuni aktif: null=belum ditanya, pending=sudah dikirim pertanyaan, renew/not_renew=sudah jawab
            $table->enum('renewal_status', ['pending_inquiry', 'will_renew', 'wont_renew'])->nullable()->after('advance_for_user_id');
            $table->timestamp('renewal_inquiry_sent_at')->nullable()->after('renewal_status');
            $table->timestamp('renewal_responded_at')->nullable()->after('renewal_inquiry_sent_at');

            $table->foreign('advance_for_user_id')->references('id')->on('users')->nullOnDelete();
        });

        // ── service_catalog ────────────────────────────────────
        Schema::create('service_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── tenant_services ────────────────────────────────────
        Schema::create('tenant_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('service_catalog')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── invoices ──────────────────────────────────────────
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->decimal('base_amount', 12, 2)->comment('Harga sewa dasar');
            $table->decimal('service_amount', 12, 2)->default(0)->comment('Total layanan tambahan');
            $table->decimal('total_amount', 12, 2)->comment('Total tagihan');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('PPh Final 0.5%');
            $table->date('period_start');
            $table->date('period_end');
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'overdue', 'cancelled'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });

        // ── payments ──────────────────────────────────────────
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 60)->unique();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['transfer_bank', 'qris', 'cash', 'midtrans'])->default('transfer_bank');
            $table->string('bank_name', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('transfer_proof', 255)->nullable()->comment('Path file bukti transfer');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('midtrans_order_id', 100)->nullable();
            $table->string('midtrans_transaction_id', 100)->nullable();
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });

        // ── in_app_notifications ───────────────────────────────
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 80)->comment('booking_advance, renewal_inquiry, invoice_due, payment_confirmed, etc.');
            $table->string('title', 200);
            $table->text('body');
            $table->string('action_url', 255)->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });

        // ── tickets ────────────────────────────────────────────
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 40)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('subject', 200);
            $table->text('description');
            $table->enum('category', ['maintenance', 'payment', 'facility', 'complaint', 'other'])->default('other');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['open', 'in_progress', 'pending', 'resolved', 'closed'])->default('open');
            $table->string('attachment', 255)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->integer('rating')->nullable()->comment('1-5 bintang dari penghuni');
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->index(['user_id', 'status']);
        });

        // ── ticket_replies ─────────────────────────────────────
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment', 255)->nullable();
            $table->timestamps();
        });

        // ── tax_reports ────────────────────────────────────────
        Schema::create('tax_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period', 7)->comment('Format: YYYY-MM');
            $table->decimal('total_revenue', 14, 2)->default(0);
            $table->decimal('tax_rate', 5, 4)->default(0.005)->comment('0.5% PPh Final');
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->integer('paid_invoices')->default(0);
            $table->decimal('ytd_revenue', 14, 2)->default(0)->comment('Year-to-date revenue');
            $table->decimal('tax_threshold', 14, 2)->default(500000000)->comment('Rp 500 juta');
            $table->timestamps();

            $table->unique('period');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['advance_for_user_id']);
            $table->dropColumn(['booking_type','advance_for_user_id','renewal_status','renewal_inquiry_sent_at','renewal_responded_at']);
        });
        Schema::dropIfExists('tax_reports');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('in_app_notifications');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('tenant_services');
        Schema::dropIfExists('service_catalog');
    }
};
