<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s]+$/',
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.min'       => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah terdaftar. Silakan <a href="' . route('login') . '">login</a>.',
            'phone.regex'    => 'Format nomor HP tidak valid.',
        ]);

        $role = Role::where('name', 'prospective_tenant')->first();

        if (!$role) {
            return back()->with('error', 'Terjadi kesalahan sistem. Hubungi admin.');
        }

        $user = User::create([
            'role_id'    => $role->id,
            'name'       => trim($request->name),
            'email'      => strtolower(trim($request->email)),
            'phone'      => $request->phone,
            'is_active'  => true,
        ]);

        LoginController::generateAndSendOtp($user->email, 'register', $user->name);

        session(['register_email' => $user->email]);

        return redirect()->route('otp.verify')
            ->with('success', 'Akun berhasil dibuat! Kode OTP telah dikirim ke ' . $user->email . '. Silakan verifikasi untuk melanjutkan.');
    }
}
