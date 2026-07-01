<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
   public function show()
    {
        $user = auth()->user();

        $view = match((int) $user->role_id) {
            1 => 'profile-views.owner.profile',
            2 => 'profile-views.admin.profile',
            3 => 'profile-views.tenant.profile',
            4 => 'profile-views.prospective.profile',
            default => 'profile-views.admin.profile',
        };

        $activeBooking = null;
        if ((int) $user->role_id === 3) {
            $activeBooking = \App\Models\Booking::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'active'])
                ->latest()
                ->first();
        }

        return view($view, compact('user', 'activeBooking'));
    }

    public function edit()
    {
        $user = auth()->user();

        $view = match((int) $user->role_id) {
            1 => 'profile-views.owner.profile-edit',
            2 => 'profile-views.admin.profile-edit',
            3 => 'profile-views.tenant.profile-edit',
            4 => 'profile-views.prospective.profile-edit',
            default => 'profile-views.admin.profile-edit',
        };

        $activeBooking = null;
        if ((int) $user->role_id === 3) {
            $activeBooking = \App\Models\Booking::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'active'])
                ->latest()
                ->first();
        }

        return view($view, compact('user', 'activeBooking'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:50',
        ]);

        Auth::user()->update($request->only('name', 'phone'));

        if ($request->filled('emergency_contact') || $request->has('emergency_contact')) {
            $activeBooking = \App\Models\Booking::where('user_id', Auth::id())
                ->whereIn('status', ['approved', 'active'])
                ->latest()
                ->first();

            if ($activeBooking) {
                $activeBooking->update([
                    'emergency_contact' => $request->emergency_contact,
                ]);
            }
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
