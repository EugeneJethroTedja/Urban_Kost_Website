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
    Schema::table('renewals', function (Blueprint $table) {
        if (!\Schema::hasColumn('renewals', 'current_end_date'))
            $table->date('current_end_date')->nullable()->after('new_end_date');

        if (!\Schema::hasColumn('renewals', 'service_amount_monthly'))
            $table->decimal('service_amount_monthly', 15, 2)->default(0)->after('amount');

        if (!\Schema::hasColumn('renewals', 'additional_services'))
            $table->json('additional_services')->nullable()->after('service_amount_monthly');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            //
        });
    }
};
