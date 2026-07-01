<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OtpVerifiedMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!session('otp_verified')) {
            return redirect()->route('otp.verify')
                ->with('info', 'Silakan verifikasi OTP Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
