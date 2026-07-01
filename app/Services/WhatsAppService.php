<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $token;
    private string $apiUrl = 'https://api.fonnte.com/send';
    private bool   $enabled;

    public function __construct()
    {
        $this->token   = config('services.fonnte.token', '');
        $this->enabled = filter_var(env('WHATSAPP_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function send(string $phone, string $message): bool
    {
        $phone = $this->normalizePhone($phone);

        if (!$this->enabled || empty($this->token) || empty($phone)) {
            Log::info("[WhatsApp DISABLED/SKIP] To: {$phone} | Msg: " . substr($message, 0, 80));
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target'  => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("[WhatsApp SENT] To: {$phone}");
                return true;
            }

            Log::warning("[WhatsApp FAILED] To: {$phone} | Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("[WhatsApp ERROR] {$e->getMessage()}");
            return false;
        }
    }

    public function sendNewBookingAdmin(string $adminPhone, array $data): bool
    {
        $type = isset($data['is_advance']) && $data['is_advance'] ? ' *ADVANCE BOOKING*' : ' *BOOKING BARU*';
        $msg  = "{$type}\n\n"
              . " *Urban Kost*\n"
              . "─────────────────\n"
              . " Pemohon: {$data['name']}\n"
              . " HP: {$data['phone']}\n"
              . " Kamar: {$data['room']}\n"
              . " Tgl Masuk: {$data['check_in']}\n"
              . " Durasi: {$data['duration']} bulan\n"
              . " Kode: {$data['booking_code']}\n"
              . "─────────────────\n"
              . "Silakan login ke dashboard untuk mereview permohonan ini.\n"
              . config('app.url');

        return $this->send($adminPhone, $msg);
    }

    public function sendBookingConfirmation(string $phone, array $data): bool
    {
        $status = $data['status'] === 'approved' ? ' *DISETUJUI*' : ' *DITOLAK*';
        $msg    = "Halo *{$data['name']}*,\n\n"
                . "Status booking Anda:\n{$status}\n\n"
                . " Kode Booking: {$data['booking_code']}\n"
                . " Kamar: {$data['room']}\n"
                . " Tgl Masuk: {$data['check_in']}\n";

        if ($data['status'] === 'approved') {
            $msg .= "\nSelamat! Kamar sudah direservasi untuk Anda.\n"
                  . "Silakan login untuk melanjutkan proses pembayaran.\n";
        } elseif (!empty($data['reason'])) {
            $msg .= "\n Alasan: {$data['reason']}\n"
                  . "Silakan cek kamar lain yang tersedia di website kami.\n";
        }

        $msg .= "\n" . config('app.url');
        return $this->send($phone, $msg);
    }

    public function sendPaymentReminder(string $phone, array $data, string $stage): bool
    {
        $stages = [
            'H-3' => [' Pengingat Tagihan', '3 hari lagi'],
            'H-2' => [' Pengingat Tagihan', '2 hari lagi'],
            'H-1' => [' Tagihan Hampir Jatuh Tempo!', 'besok'],
            'H-0' => [' TAGIHAN JATUH TEMPO HARI INI!', 'hari ini'],
        ];

        [$emoji, $waktu] = $stages[$stage] ?? [' Pengingat', 'segera'];

        $msg = "Halo *{$data['name']}*,\n\n"
             . "{$emoji}\n\n"
             . " Tagihan sewa Anda jatuh tempo *{$waktu}*.\n\n"
             . " Invoice: {$data['invoice_number']}\n"
             . " Total: *{$data['amount']}*\n"
             . " Jatuh Tempo: {$data['due_date']}\n"
             . " Kamar: {$data['room']}\n\n"
             . "Bayar sekarang di:\n"
             . config('app.url') . "/calon-penghuni/tagihan/{$data['invoice_id']}\n\n"
             . "Hubungi admin: " . config('services.admin_phone', '08123456789');

        return $this->send($phone, $msg);
    }

    public function sendPaymentConfirmed(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . "*PEMBAYARAN DIKONFIRMASI (LUNAS!)*\n\n"
             . " Invoice: {$data['invoice_number']}\n"
             . " Jumlah: *{$data['amount']}*\n"
             . " Periode: {$data['period']}\n"
             . " Kamar: {$data['room']}\n"
             . "Akun Anda sedang diproses untuk aktivasi sebagai penghuni. "
             . "Kami akan menghubungi Anda segera setelah aktivasi selesai. \n\n"
             . config('app.url');

        return $this->send($phone, $msg);
    }

    public function sendAdminInvoicePaidAlert(string $adminPhone, array $data): bool
    {
        $msg = " *TAGIHAN LUNAS — PERLU AKTIVASI*\n\n"
             . " *Urban Kost*\n"
             . "─────────────────\n"
             . " Calon Penghuni: {$data['user_name']}\n"
             . " Kamar: {$data['room']}\n"
             . " Invoice: {$data['invoice_number']}\n"
             . " Jumlah: *{$data['amount']}*\n"
             . "─────────────────\n"
             . " *Tindakan diperlukan:*\n"
             . "Aktifkan akun calon penghuni menjadi penghuni tetap di:\n"
             . config('app.url') . "/admin/tagihan/{$data['invoice_id']}\n\n"
             . "Hubungi calon penghuni jika dibutuhkan.";

        return $this->send($adminPhone, $msg);
    }

    public function sendNewTicketAdmin(string $adminPhone, array $data): bool
    {
        $priorityEmoji = match ($data['priority']) {
            'urgent' => ' URGENT',
            'high'   => ' High',
            'normal' => ' Normal',
            default  => ' Low',
        };

        $msg = " *TIKET KELUHAN BARU*\n\n"
             . "Prioritas: *{$priorityEmoji}*\n\n"
             . " Dari: {$data['name']}\n"
             . " Kamar: {$data['room']}\n"
             . " No. Tiket: {$data['ticket_number']}\n"
             . " Kategori: " . ucfirst($data['category']) . "\n"
             . " Subjek: {$data['subject']}\n\n"
             . "Login untuk menangani tiket ini:\n"
             . config('app.url') . "/admin/tickets/{$data['ticket_id']}";

        return $this->send($adminPhone, $msg);
    }

    public function sendTicketResolved(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *TIKET ANDA DISELESAIKAN*\n\n"
             . " No. Tiket: {$data['ticket_number']}\n"
             . " Subjek: {$data['subject']}\n\n"
             . "Catatan admin: {$data['note']}\n\n"
             . "Mohon berikan penilaian:\n"
             . config('app.url') . "/tenant/tiket/{$data['ticket_id']}";

        return $this->send($phone, $msg);
    }

    public function sendRenewalInquiry(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *KONFIRMASI PERPANJANGAN SEWA*\n\n"
             . "Ada calon penghuni yang berminat menempati kamar *{$data['room']}* "
             . "mulai tanggal *{$data['check_in']}*.\n\n"
             . "Apakah Anda akan *memperpanjang sewa*?\n\n"
             . "Silakan konfirmasi & Jika ingin perpanjang silahkan isi form perpanjangan:\n"
             . config('app.url') . "/tenant/perpanjangan/{$data['booking_id']}\n\n"
             . " Harap konfirmasi dalam 48 jam.\n"
             . "Hubungi admin: " . config('services.admin_phone', '08123456789');

        return $this->send($phone, $msg);
    }

    public function sendWelcomeTenant(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *SELAMAT DATANG, PENGHUNI AKTIF!*\n\n"
             . "Akun Anda telah diaktifkan sebagai Penghuni Aktif Urban Kost.\n\n"
             . " Kamar: {$data['room']}\n"
             . " Email: {$data['email']}\n\n"
             . "Silakan login ke dashboard penghuni Anda:\n"
             . config('app.url') . "/login\n\n"
             . "Selamat menikmati fasilitas Urban Kost! ";

        return $this->send($phone, $msg);
    }

    public function sendWaitingListApproved(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *ANDA MASUK WAITING LIST!*\n\n"
             . "Permohonan advance booking Anda telah disetujui admin.\n\n"
             . " Kamar: {$data['room']}\n"
             . " Posisi Antrean: #{$data['position']}\n"
             . " Kode Booking: {$data['booking_code']}\n\n"
             . "─────────────────\n"
             . " *LANGKAH SELANJUTNYA:*\n"
             . "Bayar DP sebesar *{$data['dp_amount']}* untuk mengamankan posisi Anda.\n\n"
             . " Jika Anda membatalkan posisi waiting list setelah DP dibayar, "
             . "akan dikenakan *denda 10%* dari jumlah DP.\n\n"
             . "Pantau status waiting list Anda di:\n"
             . config('app.url') . "/calon-penghuni/waiting-list/{$data['waiting_list_id']}\n\n"
             . "Hubungi admin: " . config('services.admin_phone', '08123456789');

        return $this->send($phone, $msg);
    }

    public function sendWaitingListDpPaid(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *DP WAITING LIST DITERIMA!*\n\n"
             . " Kamar: {$data['room']}\n"
             . " Posisi: #{$data['position']}\n"
             . " DP Terbayar: {$data['dp_paid']}\n\n"
             . "Posisi Anda di waiting list resmi diamankan. "
             . "Kami akan menghubungi Anda segera setelah kamar tersedia.\n\n"
             . "Pantau status di:\n"
             . config('app.url') . "/calon-penghuni/waiting-list/{$data['waiting_list_id']}";

        return $this->send($phone, $msg);
    }

    public function sendRoomAvailableWaitingList(string $phone, array $data): bool
    {
        $msg = "Halo *{$data['name']}*,\n\n"
             . " *KAMAR SUDAH TERSEDIA!*\n\n"
             . "Kamar yang Anda tunggu sudah kosong dan siap ditempati!\n\n"
             . " Kamar: {$data['room']}\n"
             . " Durasi Sewa: {$data['duration']} bulan\n"
             . " DP Terbayar: {$data['dp_paid']}\n"
             . " Sisa Pembayaran: *{$data['remaining']}*\n\n"
             . "─────────────────\n"
             . " *SEGERA LAKUKAN:*\n"
             . "Lunasi sisa pembayaran untuk resmi menjadi penghuni kamar ini.\n\n"
             . " Jika tidak ada respons dalam *3×24 jam*, posisi Anda di waiting list "
             . "dapat dipindahkan ke calon penghuni lain.\n\n"
             . "Bayar sekarang di:\n"
             . config('app.url') . "/calon-penghuni/waiting-list/{$data['waiting_list_id']}\n\n"
             . "Hubungi admin: " . config('services.admin_phone', '08123456789');

        return $this->send($phone, $msg);
    }

    public function sendWaitingListCancelled(string $phone, array $data): bool
    {
        $penaltyLine = $data['penalty']
            ? " *Denda Pembatalan: {$data['penalty']}* ({$data['penalty_pct']} dari DP)\n"
              . "Denda akan dipotong dari refund DP Anda.\n"
            : " Tidak ada denda karena DP belum dibayar.\n";

        $msg = "Halo *{$data['name']}*,\n\n"
             . " *WAITING LIST DIBATALKAN*\n\n"
             . "Posisi waiting list Anda untuk kamar *{$data['room']}* telah dibatalkan.\n\n"
             . " Alasan: {$data['reason']}\n\n"
             . "─────────────────\n"
             . $penaltyLine
             . "\n"
             . "Silakan cek kamar lain yang tersedia:\n"
             . config('app.url') . "/#kamar\n\n"
             . "Hubungi admin jika ada pertanyaan: " . config('services.admin_phone', '08123456789');

        return $this->send($phone, $msg);
    }

    public function sendWaitingListCancelledAdmin(string $adminPhone, array $data): bool
    {
        $msg = " *WAITING LIST DIBATALKAN*\n\n"
             . " *Urban Kost*\n"
             . "─────────────────\n"
             . " Nama: {$data['tenant_name']}\n"
             . " Kamar: {$data['room']}\n"
             . " Alasan: {$data['reason']}\n"
             . " Denda: {$data['penalty']}\n"
             . "─────────────────\n"
             . "Kamar ini kini *tidak memiliki waiting list aktif*.\n"
             . "Calon penghuni baru dapat mendaftar advance booking.\n\n"
             . "Lihat detail di:\n"
             . config('app.url') . "/admin/waiting-list/{$data['waiting_list_id']}";

        return $this->send($adminPhone, $msg);
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
