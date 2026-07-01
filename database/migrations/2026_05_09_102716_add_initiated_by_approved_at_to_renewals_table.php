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
        $table->enum('initiated_by', ['tenant', 'advance_booking'])
              ->default('tenant')
              ->after('status');
        $table->timestamp('approved_at')->nullable()->after('reviewed_at');
    });
}

public function down(): void
{
    Schema::table('renewals', function (Blueprint $table) {
        $table->dropColumn(['initiated_by', 'approved_at']);
    });
}
};
