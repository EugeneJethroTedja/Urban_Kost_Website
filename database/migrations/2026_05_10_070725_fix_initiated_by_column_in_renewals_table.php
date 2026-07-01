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
    DB::statement("ALTER TABLE renewals MODIFY COLUMN initiated_by ENUM('admin', 'system', 'tenant', 'advance_booking') NULL");
}

public function down(): void
{
    DB::statement("ALTER TABLE renewals MODIFY COLUMN initiated_by ENUM('admin', 'system') NULL");
}
};
