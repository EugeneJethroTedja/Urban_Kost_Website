<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiringTenancies extends Command
{
    protected $signature   = 'tenancy:check-expiring';
    protected $description = 'Kirim notifikasi ke penghuni yang masa sewanya akan berakhir dalam 10 hari';

    public function handle(NotificationService $notif): void
    {
        $today      = Carbon::today();
        $targetDate = $today->copy()->addDays(10)->toDateString();

        $bookings = Booking::whereIn('status', ['approved', 'active'])
            ->with(['user', 'room', 'activeRenewal'])
            ->get()
            ->filter(fn(Booking $b) => $this->resolveExpiryDate($b) === $targetDate);

        $this->info("Ditemukan {$bookings->count()} booking yang berakhir pada {$targetDate}.");

        foreach ($bookings as $booking) {
            $user = $booking->user;
            if (!$user) continue;

            $daysLeft = (int) $today->diffInDays(Carbon::parse($this->resolveExpiryDate($booking)));

            try {
                $notif->notifyTenancyExpiringSoon($user, $booking, $daysLeft);
                $this->info("  Notif → {$user->name} (Kamar {$booking->room?->room_number}, sisa {$daysLeft} hari)");
            } catch (\Throwable $e) {
                $this->error("  Gagal → {$user->name}: {$e->getMessage()}");
            }
        }

        $this->info('Selesai.');
    }

    private function resolveExpiryDate(Booking $booking): string
    {
        if ($booking->renewal_id && $booking->activeRenewal?->new_end_date) {
            return Carbon::parse($booking->activeRenewal->new_end_date)->toDateString();
        }

        return Carbon::parse($booking->check_in_date)
            ->addMonths($booking->duration_months)
            ->toDateString();
    }
}