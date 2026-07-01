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
    Schema::table('invoices', function (Blueprint $table) {
        $table->string('type')->default('monthly')->after('invoice_number');
        // 'monthly'  → tagihan bulanan biasa
        // 'renewal'  → perpanjangan sewa
        // 'dp'       → down payment waiting list
        $table->timestamp('paid_at')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropColumn(['type', 'paid_at']);
    });
}
};
