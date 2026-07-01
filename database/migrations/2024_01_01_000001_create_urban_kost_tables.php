<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. roles ─────────────────────────────────────────
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100);
            $table->timestamps();
        });

        // ── 2. users ──────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('phone', 25)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('otp_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // ── 3. otps ───────────────────────────────────────────
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email', 150)->index();
            $table->string('otp', 10);
            $table->enum('type', ['login', 'register'])->default('login');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['email', 'otp']);
            $table->index('expires_at');
        });

        // ── 4. rooms ──────────────────────────────────────────
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number', 20)->unique();
            $table->string('room_name', 100)->nullable();
            $table->decimal('price', 12, 2);
            $table->string('size', 50)->nullable();
            $table->integer('floor')->default(1);
            $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance'])->default('available');
            $table->text('description')->nullable();
            $table->json('facilities')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });

        // ── 5. bookings ───────────────────────────────────────
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('booking_code', 50)->unique();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->date('check_in_date');
            $table->integer('duration_months')->default(1);
            $table->string('full_name', 100);
            $table->string('phone', 25);
            $table->string('identity_number', 30)->nullable();
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });

        // ── 6. chatbot_responses ──────────────────────────────
        Schema::create('chatbot_responses', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->json('keywords');
            $table->text('response');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_responses');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('otps');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
