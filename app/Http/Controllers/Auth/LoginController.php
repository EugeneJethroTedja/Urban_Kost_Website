<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($request->email));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar. Silakan <a href="' . route('register') . '">daftar</a> terlebih dahulu.')
                         ->withInput();
        }

        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        $otp = $this->generateAndSendOtp($email, 'login', $user->name);

        session(['login_email' => $email]);

        return redirect()->route('otp.verify')
            ->with('success', 'Kode OTP telah dikirim ke ' . $email . '. Berlaku selama ' . config('app.otp_expiry', 10) . ' menit.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil logout.');
    }

    public static function generateAndSendOtp(string $email, string $type, string $userName = ''): string
    {
        Otp::invalidatePrevious($email, $type);

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'email'      => $email,
            'otp'        => $otp,
            'type'       => $type,
            'expires_at' => now()->addMinutes(env('OTP_EXPIRY_MINUTES', 10)),
            'is_used'    => false,
        ]);

        try {
            Mail::to($email)->send(new OtpMail($otp, $type, $userName));
        } catch (\Exception $e) {
            \Log::error('OTP Mail Error: ' . $e->getMessage());
        }

        return $otp;
    }
}
