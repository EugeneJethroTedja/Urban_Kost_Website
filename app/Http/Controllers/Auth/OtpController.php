<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        $email = session('login_email') ?? session('register_email');

        if (!$email && !auth()->check()) {
            return redirect()->route('login')->with('error', 'Sesi habis. Silakan mulai ulang.');
        }

        return view('auth.otp', ['email' => $email ?? auth()->user()?->email]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits'   => 'Kode OTP harus 6 digit angka.',
        ]);

        $email = session('login_email') ?? session('register_email') ?? auth()->user()?->email;

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi habis. Silakan login kembali.');
        }

        $otpRecord = Otp::where('email', $email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP salah. Periksa kembali kode yang dikirim ke email Anda.')->withInput();
        }

        if ($otpRecord->isExpired()) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.')->withInput();
        }

        $otpRecord->update(['is_used' => true]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        if (!auth()->check()) {
            Auth::login($user, false);
        }

        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        session(['otp_verified' => true]);

        session()->forget(['login_email', 'register_email']);

        $route = $user->getDashboardRoute();
        return redirect()->route($route)->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function resend(Request $request)
    {
        $email = session('login_email') ?? session('register_email') ?? auth()->user()?->email;

        if (!$email) {
            return redirect()->route('login')->with('error', 'Sesi habis. Silakan login kembali.');
        }

        $user = User::where('email', $email)->first();
        $type = session('login_email') ? 'login' : 'register';

        LoginController::generateAndSendOtp($email, $type, $user?->name ?? '');

        return back()->with('success', 'Kode OTP baru telah dikirim ke ' . $email . '.');
    }
}