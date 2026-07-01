<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            if (!Schema::hasColumn('renewals', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('renewals', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
            if (!Schema::hasColumn('renewals', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('renewals', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('renewals', 'new_end_date')) {
                $table->date('new_end_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('renewals', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('renewals', 'reviewed_by')      ? 'reviewed_by'      : null,
                Schema::hasColumn('renewals', 'reviewed_at')      ? 'reviewed_at'      : null,
                Schema::hasColumn('renewals', 'approved_at')      ? 'approved_at'      : null,
                Schema::hasColumn('renewals', 'rejection_reason') ? 'rejection_reason' : null,
                Schema::hasColumn('renewals', 'new_end_date')     ? 'new_end_date'     : null,
            ]));
        });
    }
};