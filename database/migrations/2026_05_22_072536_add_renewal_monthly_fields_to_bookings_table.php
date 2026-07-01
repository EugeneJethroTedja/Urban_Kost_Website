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
    Schema::table('bookings', function (Blueprint $table) {
        if (!Schema::hasColumn('bookings', 'renewal_id'))
            $table->unsignedBigInteger('renewal_id')->nullable()->after('monthly_invoice_count');

        if (!Schema::hasColumn('bookings', 'renewal_payment_method'))
            $table->string('renewal_payment_method')->nullable()->after('renewal_id');

        if (!Schema::hasColumn('bookings', 'renewal_monthly_count'))
            $table->integer('renewal_monthly_count')->default(0)->after('renewal_payment_method');

        if (!Schema::hasColumn('bookings', 'renewal_duration'))
            $table->integer('renewal_duration')->nullable()->after('renewal_monthly_count');

        if (!Schema::hasColumn('bookings', 'renewal_period_start'))
            $table->date('renewal_period_start')->nullable()->after('renewal_duration');

        if (!Schema::hasColumn('bookings', 'renewal_monthly_day'))
            $table->integer('renewal_monthly_day')->nullable()->after('renewal_period_start');

        if (!Schema::hasColumn('bookings', 'renewal_service_monthly'))
            $table->integer('renewal_service_monthly')->default(0)->after('renewal_monthly_day');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn([
            'renewal_id',
            'renewal_payment_method',
            'renewal_monthly_count',
            'renewal_duration',
            'renewal_period_start',
            'renewal_monthly_day',
            'renewal_service_monthly',
        ]);
    });
}
};
