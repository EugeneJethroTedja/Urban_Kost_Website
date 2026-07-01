<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('waiting_lists', function (Blueprint $table) {
        // dp_invoice_id sudah ada — skip
        // remaining_invoice_id sudah ada, kita tambah alias full_invoice_id
        // ATAU kita pakai remaining_invoice_id yang sudah ada

        // Hanya tambah jika belum ada
        if (!Schema::hasColumn('waiting_lists', 'full_invoice_id')) {
            $table->unsignedBigInteger('full_invoice_id')
                  ->nullable()
                  ->after('dp_invoice_id');
            $table->foreign('full_invoice_id')
                  ->references('id')->on('invoices')
                  ->nullOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('waiting_lists', function (Blueprint $table) {
        if (Schema::hasColumn('waiting_lists', 'full_invoice_id')) {
            $table->dropForeign(['full_invoice_id']);
            $table->dropColumn('full_invoice_id');
        }
    });
}
};
