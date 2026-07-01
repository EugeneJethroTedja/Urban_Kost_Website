<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Booking;
use App\Models\InAppNotification;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\Role;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\WaitingList;
use App\Services\NotificationService;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_rooms'      => Room::count(),
            'available_rooms'  => Room::where('status', 'available')->count(),
            'occupied_rooms'   => Room::where('status', 'occupied')->count(),
            'total_tenants'    => User::whereHas('role', fn($q) => $q->where('name', 'tenant'))->where('is_active', true)->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_bookings'   => Booking::count(),
            'open_tickets'     => \App\Models\Ticket::whereIn('status', ['open', 'in_progress'])->count(),
            'urgent_tickets'   => \App\Models\Ticket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
            'pending_payments' => Invoice::where('status', 'pending_verification')->count(),
            'active_waiting'   => WaitingList::whereIn('status', ['waiting', 'notified'])->count(),
        ];

        $recentBookings = Booking::with(['user', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }

    public function listRooms()
    {
        $query = Room::orderBy('room_number');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                ->orWhere('room_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $rooms = $query->with('images')->paginate(10)->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function createRoom()
    {
        return view('admin.rooms.create');
    }
 
    public function storeRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'room_name'   => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'size'        => 'nullable|string|max:50',
            'floor'       => 'nullable|integer|min:1',
            'status'      => 'required|in:available,occupied,reserved,maintenance',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|max:2048',
        ], [
            'images.max'    => 'Maksimal 5 foto per kamar.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max'   => 'Ukuran setiap foto maksimal 2 MB.',
        ]);
    
        $data = $request->except(['images', '_token']);
        $data['facilities'] = $request->facilities ?? [];

        $room = Room::create($data);

        if ($request->hasFile('images')) {
            $files     = $request->file('images');
            $isPrimary = true; 
    
            foreach ($files as $index => $image) {
                $path = $image->store('rooms', 'public');
    
                RoomImage::create([
                    'room_id'    => $room->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                    'is_primary' => $isPrimary,
                ]);
    
                if ($isPrimary) {
                    $room->update(['image_path' => $path]);
                    $isPrimary = false;
                }
            }
        }
    
        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar <strong>' . $room->room_number . '</strong> berhasil ditambahkan.');
    }

    public function editRoom($id)
    {
        $room = Room::with('images')->findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Room::with('images')->findOrFail($id);
    
        $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $id,
            'room_name'   => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:available,occupied,reserved,maintenance',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ], [
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max'   => 'Ukuran setiap foto maksimal 2 MB.',
        ]);
    
        $data = $request->except(['images', '_token', '_method']);
        $data['facilities'] = $request->facilities ?? [];
    
        $room->update($data);
    
        if ($request->hasFile('images')) {
            $existingCount = $room->images()->count();
            $files         = $request->file('images');
            $slotsLeft     = 5 - $existingCount; 
    
            if ($slotsLeft > 0) {
                foreach (array_slice($files, 0, $slotsLeft) as $index => $image) {
                    $path = $image->store('rooms', 'public');
    
                    $isFirstEver = ($existingCount === 0 && $index === 0);
    
                    RoomImage::create([
                        'room_id'    => $room->id,
                        'image_path' => $path,
                        'sort_order' => $existingCount + $index,
                        'is_primary' => $isFirstEver,
                    ]);
    
                    if ($isFirstEver) {
                        $room->update(['image_path' => $path]);
                    }
                }
    
                $skipped = count($files) - $slotsLeft;
                if ($skipped > 0) {
                    session()->flash('warning',
                        ' ' . $skipped . ' foto tidak diunggah karena sudah mencapai batas maksimal 5 foto per kamar.'
                    );
                }
            } else {
                session()->flash('warning',
                    ' Kamar ini sudah memiliki 5 foto (batas maksimal). Hapus foto lama terlebih dahulu untuk menambah foto baru.'
                );
            }
        }
    
        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Data kamar <strong>' . $room->room_number . '</strong> berhasil diperbarui.');
    }

    public function destroyRoom($id)
    {
        $room = Room::with('images')->findOrFail($id);
    
        if ($room->status === 'occupied') {
            return back()->with('error', 'Kamar tidak bisa dihapus karena sedang ditempati.');
        }
    
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        if ($room->image_path && $room->images->isEmpty()) {
            Storage::disk('public')->delete($room->image_path);
        }

        $room->delete();
    
        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
 
    public function destroyRoomImage(Request $request, $roomId, $imageId)
    {
        $room  = Room::with('images')->findOrFail($roomId);
        $image = $room->images()->findOrFail($imageId);
    
        $wasPrimary = $image->is_primary;
    
        Storage::disk('public')->delete($image->image_path);
    
        $image->delete();
    
        if ($wasPrimary) {
            $next = $room->images()->orderBy('sort_order')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
                $room->update(['image_path' => $next->image_path]);
            } else {
                $room->update(['image_path' => null]);
            }
        }
    
        return back()->with('success', 'Foto berhasil dihapus.');
    }
 
    public function setRoomImagePrimary(Request $request, $roomId, $imageId)
    {
        $room  = Room::with('images')->findOrFail($roomId);
        $image = $room->images()->findOrFail($imageId);

        $room->images()->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        $room->update(['image_path' => $image->image_path]);
    
        return back()->with('success', 'Foto utama berhasil diperbarui.');
    }
 
    public function reorderRoomImages(Request $request, $roomId)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:room_images,id',
        ]);
    
        $room = Room::findOrFail($roomId);
    
        foreach ($request->order as $sortOrder => $imageId) {
            $room->images()->where('id', $imageId)->update([
                'sort_order' => $sortOrder,
            ]);
        }
    
        return response()->json(['success' => true]);
    }

    public function listTenants()
    {
        $query = User::whereHas('role', fn($q) => $q->where('name', 'tenant'))
            ->orderBy('created_at', 'desc');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = request('status')) {
            $query->where('is_active', $status === 'active');
        }
    
        $tenants = $query->paginate(10)->withQueryString();
    
        return view('admin.tenants.index', compact('tenants'));
    }

    public function editTenant($id)
    {
        $tenant = User::with(['bookings', 'role'])->findOrFail($id);
        $roles  = Role::whereNotIn('name', ['admin', 'owner'])->orderBy('name')->get();
    
        return view('admin.tenants.edit', compact('tenant', 'roles'));
    }

    public function updateTenant(Request $request, $id)
    {
        $tenant = User::findOrFail($id);
    
        $request->validate([
            'name'        => 'required|string|min:2|max:100',
            'email'       => 'required|email|unique:users,email,' . $id,
            'phone'       => 'nullable|string|max:25',
            'role_id'     => 'required|exists:roles,id',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
    
        $wasActive  = $tenant->is_active;
        $isNowActive = (bool) $request->has('is_active');
    
        $tenant->update([
            'name'        => trim($request->name),
            'email'       => strtolower(trim($request->email)),
            'phone'       => $request->phone,
            'role_id'     => $request->role_id,
            'is_active'   => $isNowActive,
            'admin_notes' => $request->admin_notes,
        ]);
    
        if (!$wasActive && $isNowActive) {
            \App\Http\Controllers\Auth\LoginController::generateAndSendOtp(
                $tenant->email, 'login', $tenant->name
            );
        }
    
        return redirect()
            ->route('admin.tenants.show', $tenant->id)
            ->with('success', ' Data penghuni <strong>' . $tenant->name . '</strong> berhasil diperbarui.');
    }
 
    public function destroyTenant($id)
    {
        $tenant = User::findOrFail($id);
    
        if ($tenant->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
    
        if (in_array($tenant->role?->name, ['admin', 'owner'])) {
            return back()->with('error', 'Akun dengan role ' . $tenant->role->display_name . ' tidak bisa dihapus dari sini.');
        }
    
        $tenantName = $tenant->name;
    
        $activeBookings = $tenant->bookings()
            ->whereIn('status', ['approved', 'active'])
            ->with('room')
            ->get();
    
        foreach ($activeBookings as $booking) {
            $booking->room?->update(['status' => 'available']);
            $booking->update(['status' => 'cancelled']);
        }
    
        \App\Models\WaitingList::where('user_id', $tenant->id)
            ->whereIn('status', ['waiting', 'notified'])
            ->update(['status' => 'cancelled']);
    
        $tenant->delete();
    
        return redirect()
            ->route('admin.tenants.index')
            ->with('success', ' Penghuni <strong>' . $tenantName . '</strong> berhasil dihapus.');
    }

    public function createTenant()
    {
        $prospectives = User::whereHas('role', fn($q) => $q->where('name', 'prospective_tenant'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.tenants.create', compact('prospectives'));
    }

    public function storeTenant(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:25',
        ]);

        $email      = strtolower(trim($request->email));
        $tenantRole = Role::where('name', 'tenant')->firstOrFail();

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $existingRoleName = $existingUser->role?->name;

            if ($existingRoleName === 'tenant') {
                return back()
                    ->with('error', 'Email <strong>' . $email . '</strong> sudah terdaftar sebagai Penghuni Aktif.')
                    ->withInput();
            }

            if (in_array($existingRoleName, ['admin', 'owner'])) {
                return back()
                    ->with('error', 'Email sudah digunakan oleh akun ' . $existingUser->role?->display_name . '.')
                    ->withInput();
            }

            $existingUser->update([
                'role_id'    => $tenantRole->id,
                'name'       => trim($request->name),
                'phone'      => $request->phone ?? $existingUser->phone,
                'is_active'  => true,
                'created_by' => $existingUser->created_by ?? auth()->id(),
            ]);

            LoginController::generateAndSendOtp($existingUser->email, 'login', $existingUser->name);

            return redirect()->route('admin.tenants.index')
                ->with('success', ' Akun <strong>' . $existingUser->name . '</strong> berhasil diupgrade menjadi Penghuni Aktif.');
        }

        $user = User::create([
            'role_id'    => $tenantRole->id,
            'name'       => trim($request->name),
            'email'      => $email,
            'phone'      => $request->phone,
            'is_active'  => true,
            'created_by' => auth()->id(),
        ]);

        LoginController::generateAndSendOtp($user->email, 'login', $user->name);

        return redirect()->route('admin.tenants.index')
            ->with('success', ' Penghuni baru <strong>' . $user->name . '</strong> berhasil didaftarkan.');
    }

    public function upgradeTenant(Request $request, $userId)
    {
        $user = User::whereHas('role', fn($q) => $q->where('name', 'prospective_tenant'))
            ->findOrFail($userId);

        $tenantRole = Role::where('name', 'tenant')->firstOrFail();

        $user->update([
            'role_id'   => $tenantRole->id,
            'is_active' => true,
        ]);

        LoginController::generateAndSendOtp($user->email, 'login', $user->name);

        return back()->with('success', ' <strong>' . $user->name . '</strong> berhasil diupgrade menjadi Penghuni Aktif.');
    }

    public function showTenant($id)
    {
        $tenant = User::with(['bookings.room'])->findOrFail($id);
        return view('admin.tenants.show', compact('tenant'));
    }

    public function listBookings()
    {
        $query = Booking::with(['user', 'room'])->orderBy('created_at', 'desc');
    
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                                                ->orWhere('email', 'like', "%{$search}%"))
                ->orWhereHas('room', fn($r) => $r->where('room_number', 'like', "%{$search}%")
                                                ->orWhere('room_name', 'like', "%{$search}%"));
            });
        }
    
        if ($status = request('status')) {
            $query->where('status', $status);
        }
    
        if ($type = request('booking_type')) {
            $query->where('booking_type', $type);
        }
    
        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    
        $bookings = $query->paginate(15)->withQueryString();
    
        $stats = [
            'all'          => Booking::count(),
            'pending'      => Booking::where('status', 'pending')->count(),
            'approved'     => Booking::where('status', 'approved')->count(),
            'active'       => Booking::where('status', 'active')->count(),
            'waiting_list' => Booking::where('status', 'waiting_list')->count(),
            'rejected'     => Booking::where('status', 'rejected')->count(),
            'cancelled'    => Booking::where('status', 'cancelled')->count(),
            'completed'    => Booking::where('status', 'completed')->count(),
        ];
    
        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function createManualBooking()
    {
        $rooms = Room::orderBy('room_number')->get();
        $users = User::whereHas('role', fn($q) => $q->whereNotIn('name', ['admin', 'owner']))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $additionalServices = [];
        if (class_exists(\App\Models\ServiceCatalog::class)) {
            $additionalServices = \App\Models\ServiceCatalog::toBookingArray();
        }

        $roomMinDates = [];
        foreach ($rooms as $room) {
            if (in_array($room->status, ['occupied', 'reserved'])) {
                $activeTenantBooking = Booking::where('room_id', $room->id)
                    ->whereIn('status', ['approved', 'active'])
                    ->latest()
                    ->first();

                if ($activeTenantBooking?->check_in_date && $activeTenantBooking?->duration_months) {
                    $tenantEnd = Carbon::parse($activeTenantBooking->check_in_date)
                        ->addMonths($activeTenantBooking->duration_months);

                    $roomMinDates[$room->id] = $tenantEnd->isPast()
                        ? Carbon::today()->format('Y-m-d')
                        : $tenantEnd->copy()->addDay()->format('Y-m-d');
                } else {
                    $roomMinDates[$room->id] = Carbon::today()->format('Y-m-d');
                }
            } else {
                $roomMinDates[$room->id] = Carbon::today()->format('Y-m-d');
            }
        }

        return view('admin.bookings.create', compact(
            'rooms', 'users', 'additionalServices', 'roomMinDates'
        ));
    }
 
    public function storeManualBooking(Request $request)
    {
        $minCheckInString = 'today';
        $roomForValidation = Room::with([
            'bookings' => fn($q) => $q->whereIn('status', ['approved', 'active'])->latest()
        ])->find($request->room_id);

        $isAdvanceForValidation = $roomForValidation
            && in_array($roomForValidation->status, ['occupied', 'reserved']);

        if ($isAdvanceForValidation) {
            $activeTenantBooking = $roomForValidation->bookings->first();
            if ($activeTenantBooking?->check_in_date && $activeTenantBooking?->duration_months) {
                $tenantEnd = Carbon::parse($activeTenantBooking->check_in_date)
                    ->addMonths($activeTenantBooking->duration_months);
                $minCheckInString = $tenantEnd->isPast()
                    ? Carbon::today()->format('Y-m-d')
                    : $tenantEnd->copy()->addDay()->format('Y-m-d');
            } else {
                $minCheckInString = Carbon::today()->format('Y-m-d');
            }
        }

        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'room_id'         => 'required|exists:rooms,id',
            'full_name'       => 'required|string|max:100',
            'phone'           => 'required|string|max:25',
            'identity_number' => 'nullable|string|max:30',
            'payment_method' => 'nullable|in:lump_sum,monthly',
            'check_in_date'   => [
                'required', 'date',
                'after_or_equal:' . $minCheckInString,
            ],
            'duration_months' => 'required|integer|min:1|max:24',
            'booking_type'    => 'required|in:regular,advance',
            'message'         => 'nullable|string|max:500',
            'admin_notes'     => 'nullable|string|max:1000',
            'services'        => 'nullable|array',
            'auto_approve'    => 'nullable|boolean',
        ], [
            'user_id.required'         => 'Pilih pengguna yang akan dibookingkan.',
            'room_id.required'         => 'Pilih kamar yang akan dipesan.',
            'full_name.required'       => 'Nama lengkap wajib diisi.',
            'phone.required'           => 'Nomor HP wajib diisi.',
            'check_in_date.required'   => 'Tanggal masuk wajib diisi.',
            'check_in_date.after_or_equal' => $isAdvanceForValidation
                ? 'Tanggal masuk tidak boleh sebelum ' . $minCheckInString . ' (H+1 penghuni aktif selesai).'
                : 'Tanggal masuk tidak boleh sebelum hari ini.',
            'duration_months.required' => 'Durasi sewa wajib diisi.',
        ]);

        $user = User::findOrFail($request->user_id);
        $room = Room::findOrFail($request->room_id);

        $existingActive = Booking::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->first();

        if ($existingActive) {
            return back()
                ->withInput()
                ->with('error',
                    ' <strong>' . $user->name . '</strong> sudah memiliki kamar aktif ' .
                    '(booking: <strong>' . $existingActive->booking_code . '</strong>). ' .
                    'Selesaikan booking tersebut terlebih dahulu.'
                );
        }

        $existingPending = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'waiting_list'])
            ->first();

        if ($existingPending) {
            return back()
                ->withInput()
                ->with('error',
                    ' <strong>' . $user->name . '</strong> masih memiliki permohonan aktif ' .
                    '(kode: <strong>' . $existingPending->booking_code . '</strong>, ' .
                    'status: <strong>' . $existingPending->status . '</strong>). ' .
                    'Proses atau batalkan permohonan tersebut dulu.'
                );
        }

        if ($room->status === 'maintenance') {
            return back()
                ->withInput()
                ->with('error', ' Kamar <strong>' . $room->room_number . '</strong> sedang dalam perbaikan dan tidak bisa dibooking.');
        }

        $isAdvance    = $request->booking_type === 'advance';
        $roomOccupied = in_array($room->status, ['occupied', 'reserved']);

        if ($roomOccupied && !$isAdvance) {
            $isAdvance = true;
            session()->flash('warning_auto_advance',
                ' Tipe booking diubah ke <strong>Advance</strong> karena kamar sedang terisi.'
            );
        }

        if ($isAdvance) {
            $existingWL = WaitingList::where('room_id', $room->id)
                ->whereIn('status', ['waiting', 'notified'])
                ->first();

            if ($existingWL) {
                return back()
                    ->withInput()
                    ->with('error',
                        ' Kamar <strong>' . $room->room_number . '</strong> sudah memiliki ' .
                        'waiting list aktif atas nama <strong>' . ($existingWL->user?->name ?? '-') . '</strong>.'
                    );
            }
        }

        $catalogServices       = [];
        $serviceAmountPerMonth = 0;
        $serviceDetails        = [];

        if (class_exists(\App\Models\ServiceCatalog::class)) {
            $catalogServices = \App\Models\ServiceCatalog::toBookingArray();
        }

        foreach ($request->services ?? [] as $key) {
            if (isset($catalogServices[$key])) {
                $svc                   = $catalogServices[$key];
                $serviceAmountPerMonth += $svc['price'];
                $serviceDetails[$key]  = $svc;
            }
        }

        $advanceForUserId = null;
        if ($isAdvance) {
            $currentTenant = User::whereHas('role', fn($q) => $q->where('name', 'tenant'))
                ->where('is_active', true)
                ->whereHas('bookings', fn($q) => $q
                    ->where('room_id', $room->id)
                    ->whereIn('status', ['approved', 'active'])
                )
                ->first();
            $advanceForUserId = $currentTenant?->id;
        }

        $booking = Booking::create([
            'user_id'                 => $user->id,
            'room_id'                 => $room->id,
            'status'                  => 'pending',
            'booking_type'            => $isAdvance ? 'advance' : 'regular',
            'advance_for_user_id'     => $advanceForUserId,
            'renewal_status'          => $isAdvance ? 'pending_inquiry' : null,
            'renewal_inquiry_sent_at' => $isAdvance ? now() : null,
            'full_name'               => $request->full_name,
            'phone'                   => $request->phone,
            'identity_number'         => $request->identity_number,
            'check_in_date'           => $request->check_in_date,
            'duration_months'         => $request->duration_months,
            'message'                 => $request->message,
            'payment_method' => $request->payment_method ?? 'lump_sum',
            'admin_notes'             => $request->admin_notes
                                        ? '[MANUAL ADMIN] ' . $request->admin_notes
                                        : '[MANUAL ADMIN] Booking dibuat oleh admin ' . auth()->user()?->name,
            'additional_services'     => !empty($serviceDetails) ? $serviceDetails : null,
            'service_amount'          => $serviceAmountPerMonth,
            'reviewed_by'             => auth()->id(),
            'reviewed_at'             => now(),
        ]);

        if ($request->boolean('auto_approve')) {
            if ($isAdvance) {
                return $this->approveToWaitingList(new Request([
                    'admin_notes' => $booking->admin_notes,
                    'dp_amount'   => null,
                ]), $booking->id);
            } else {
                $bookingUpdate = [
                    'status'      => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ];

                if ($booking->payment_method === 'monthly') {
                    $billingDay = min((int) \Carbon\Carbon::parse($booking->check_in_date ?? now())->format('d'), 28);
                    $bookingUpdate['monthly_billing_day']   = $billingDay;
                    $bookingUpdate['monthly_invoice_count'] = 1;
                }

                $booking->update($bookingUpdate);
                $booking->room->update(['status' => 'reserved']);
                $invoice = $this->createInvoiceForBooking($booking);
                (new NotificationService())->notifyBookingApprovedWithInvoice($booking, $invoice);

                return redirect()
                    ->route('admin.bookings.show', $booking->id)
                    ->with('success',
                        ' Booking manual <strong>' . $booking->booking_code . '</strong> dibuat & langsung disetujui. ' .
                        'Invoice <strong>' . $invoice->invoice_number . '</strong> diterbitkan untuk ' .
                        '<strong>' . $user->name . '</strong>.'
                    );
            }
        }

        try {
            (new NotificationService())->notifyNewBooking($booking);
        } catch (\Exception $e) {
            Log::warning('[AdminController] notifyNewBooking gagal: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('success',
                ' Booking manual <strong>' . $booking->booking_code . '</strong> berhasil dibuat untuk ' .
                '<strong>' . $user->name . '</strong>. ' .
                'Silakan review dan approve dari halaman detail.'
            );
    }

    public function editBooking($id)
    {
        $booking = Booking::with(['user', 'room', 'invoices'])->findOrFail($id);

        $isReadOnly = in_array($booking->status, ['completed', 'cancelled', 'expired']);
    
        $rooms = \App\Models\Room::orderBy('room_number')->get();
    
        return view('admin.bookings.edit', compact('booking', 'isReadOnly', 'rooms'));
    }
 
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);
    
        if (in_array($booking->status, ['completed', 'cancelled', 'expired'])) {
            return back()->with('error', 'Booking dengan status <strong>' . $booking->status . '</strong> tidak bisa diedit.');
        }
    
        $rules = [
            'admin_notes'     => 'nullable|string|max:1000',
            'message'         => 'nullable|string|max:500',
            'phone'           => 'nullable|string|max:25',
            'full_name'       => 'nullable|string|max:100',
            'identity_number' => 'nullable|string|max:30',
        ];
    
        if (in_array($booking->status, ['pending', 'under_review'])) {
            $rules['check_in_date']   = 'nullable|date';
            $rules['duration_months'] = 'nullable|integer|min:1|max:24';
        }
    
        $data = $request->validate($rules);
    
        $data = array_filter($data, fn($v) => !is_null($v));
    
        $booking->update($data);
    
        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', ' Data booking <strong>' . $booking->booking_code . '</strong> berhasil diperbarui.');
    }

    public function destroyBooking(Request $request, $id)
    {
        $booking = Booking::with(['invoices', 'waitingList', 'room'])->findOrFail($id);

        $isAdvanceWaitingList = $booking->booking_type === 'advance'
            && $booking->status === 'waiting_list';

        $deletableStatuses = ['pending', 'rejected', 'cancelled', 'expired'];

        if (!in_array($booking->status, $deletableStatuses) && !$isAdvanceWaitingList) {
            return back()->with('error',
                ' Booking <strong>' . $booking->booking_code . '</strong> tidak bisa dihapus ' .
                'karena statusnya <strong>' . $booking->status . '</strong>. ' .
                'Hanya booking dengan status ditolak, dibatalkan, kadaluarsa, atau advance waiting list yang bisa dihapus.'
            );
        }

        $hasPaidInvoice = $booking->invoices()->where('status', 'paid')->exists();
        if ($hasPaidInvoice) {
            return back()->with('error',
                ' Booking <strong>' . $booking->booking_code . '</strong> memiliki invoice yang sudah lunas. ' .
                'Tidak bisa dihapus untuk menjaga integritas data keuangan.'
            );
        }

        $code = $booking->booking_code;

        DB::transaction(function () use ($booking, $isAdvanceWaitingList) {
            $booking->invoices()->whereNotIn('status', ['paid'])->delete();

            if ($isAdvanceWaitingList && $booking->waitingList) {
                $booking->waitingList->update(['status' => 'cancelled']);
            }

            if ($booking->waitingList && !$isAdvanceWaitingList) {
                $booking->waitingList->delete();
            }

            $booking->delete();
        });

        return redirect()->route('admin.bookings.index')
            ->with('success', ' Booking <strong>' . $code . '</strong> beserta data terkait berhasil dihapus.');
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['user', 'room', 'waitingList', 'invoices'])->findOrFail($id);

        $existingWaitingList = WaitingList::where('room_id', $booking->room_id)
            ->whereIn('status', ['waiting', 'notified'])
            ->where('booking_id', '!=', $booking->id)
            ->first();

        $oldTenantLeasePeriod = null;
        $wlEntry = $booking->waitingList ?? null;
        if ($wlEntry) {
            $readiness = $this->resolveNotifyReadiness($wlEntry);
            if ($readiness) {
                $oldTenantLeasePeriod = [
                    'is_expired'  => $readiness['is_ready'],
                    'end_date'    => $readiness['period_start'],
                    'tenant_name' => $wlEntry->user?->name,
                ];
            }
        }

        return view('admin.bookings.show', compact('booking', 'existingWaitingList', 'oldTenantLeasePeriod'));
    }

    public function approveBooking(Request $request, $id)
    {
        $booking = Booking::with('user.role', 'room')->findOrFail($id);
        $notif   = new NotificationService();

        if ($booking->booking_type === 'advance') {
            return $this->approveToWaitingList($request, $id);
        }

        if (!in_array($booking->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya (status: ' . $booking->status . ').');
        }

        if ($booking->invoices()->exists()) {
            return back()->with('warning', 'Invoice untuk booking ini sudah pernah dibuat.');
        }

        $bookingUpdate = [
            'status'      => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ];

        if (($request->payment_method ?? $booking->payment_method) === 'monthly') {
            $billingDay = min((int) \Carbon\Carbon::parse($booking->check_in_date ?? now())->format('d'), 28);
            $bookingUpdate['monthly_billing_day']   = $billingDay;
            $bookingUpdate['monthly_invoice_count'] = 1;
        }

        $booking->update($bookingUpdate);

        $booking->room->update(['status' => 'reserved']);

        $invoice = $this->createInvoiceForBooking($booking);

        $notif->notifyBookingApprovedWithInvoice($booking, $invoice);

        return back()->with('success',
            ' Booking <strong>' . $booking->booking_code . '</strong> disetujui. ' .
            'Invoice <strong>' . $invoice->invoice_number . '</strong> diterbitkan ke ' .
            '<strong>' . $booking->user?->name . '</strong>.'
        );
    }

    public function approveToWaitingList(Request $request, $id)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);
 
        if ($booking->booking_type !== 'advance') {
            return back()->with('error', 'Hanya advance booking yang bisa disetujui ke waiting list.');
        }
 
        if (!in_array($booking->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Booking ini sudah diproses (status: ' . $booking->status . ').');
        }
 
        if (WaitingList::where('room_id', $booking->room_id)
            ->whereIn('status', ['waiting', 'notified'])
            ->exists()) {
            return back()->with('error', 'Kamar ini sudah memiliki waiting list aktif.');
        }
 
        $pricePerMonth   = $booking->room?->price ?? 0;
        $duration        = $booking->duration_months ?? 1;
        $servicePerMonth = (float) ($booking->service_amount ?? 0);
        $paymentMethod   = $booking->payment_method ?? 'lump_sum';   
 
        $totalSewa  = $pricePerMonth * $duration;
        $svcTotal   = $servicePerMonth * $duration;
        $grandTotal = $totalSewa + $svcTotal;
 
        if ($request->filled('dp_amount') && (int) $request->dp_amount > 0) {
            $dpAmount = (int) $request->dp_amount;
        } elseif ($paymentMethod === 'monthly') {
            $bilanBulanPertama = $pricePerMonth + $servicePerMonth;
            $dpAmount          = (int) round($bilanBulanPertama * 0.50);
        } else {
            $dpAmount = (int) round($grandTotal * 0.50);
        }
 
        $checkInDate = $booking->check_in_date;
        $periodStart = $checkInDate?->toDateString() ?? now()->toDateString();
        $periodEnd   = $checkInDate
            ? $checkInDate->copy()->addMonths($duration)->toDateString()
            : now()->addMonths($duration)->toDateString();
 
        $dpInvoice = null;
        $wl        = null;
 
        DB::transaction(function () use ($booking, $dpAmount, $grandTotal, $request, &$wl, &$dpInvoice, $duration, $periodStart, $periodEnd, $paymentMethod) {
 
            $booking->update([
                'status'      => 'waiting_list',
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
 
            $position = WaitingList::where('room_id', $booking->room_id)
                ->whereIn('status', ['waiting', 'notified'])
                ->count() + 1;
 
            $wl = WaitingList::create([
                'booking_id'  => $booking->id,
                'room_id'     => $booking->room_id,
                'user_id'     => $booking->user_id,
                'position'    => $position,
                'status'      => 'waiting',
                'dp_amount'   => $dpAmount,
                'dp_paid'     => 0,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
 
            $dpNote = $paymentMethod === 'monthly'
                ? 'DP 50% dari biaya bulan pertama (Rp ' . number_format($dpAmount * 2, 0, ',', '.') . '/bln). ' .
                  'Metode: Bayar Bulanan. Bulan selanjutnya ditagih per bulan saat aktif.'
                : 'DP 50% dari grand total sewa Rp ' . number_format($grandTotal, 0, ',', '.') .
                  ' (' . $duration . ' bln). Metode: Bayar Lunas.';
 
            $dpInvoice = Invoice::create([
                'user_id'         => $booking->user_id,
                'room_id'         => $booking->room_id,
                'booking_id'      => $booking->id,
                'waiting_list_id' => $wl->id,
                'invoice_number'  => 'INV-DP-' . now()->format('Ymd') . '-' . str_pad($wl->id, 4, '0', STR_PAD_LEFT),
                'invoice_type' => 'waiting_list_dp',
                'invoice_type'    => 'waiting_list_dp',
                'base_amount'     => $dpAmount,
                'service_amount'  => 0,
                'total_amount'    => $dpAmount,
                'tax_amount'      => 0,
                'status'          => 'unpaid',
                'due_date'        => now()->addDays(7),
                'period_start'    => $periodStart,
                'period_end'      => $periodEnd,
                'notes'           => $dpNote . ' Rencana masuk: ' . $periodStart . '.',
            ]);
 
            try {
                $wl->update(['dp_invoice_id' => $dpInvoice->id]);
            } catch (\Exception $e) {
            }
        });
 
        InAppNotification::send(
            $booking->user_id,
            'booking_waiting_list',
            ' Anda Masuk Waiting List!',
            'Booking kamar ' . $booking->room?->room_number . ' disetujui masuk Waiting List. ' .
                'Silakan bayar DP sebesar Rp ' . number_format($dpAmount, 0, ',', '.') . ' via menu Tagihan.',
            route('prospective.invoices.index'),
            ['waiting_list_id' => $wl?->id]
        );
 
        try {
            (new NotificationService())->notifyWaitingListApproved($wl);
        } catch (\Exception $e) {
        }
 
        $dpLabel = $booking->payment_method === 'monthly'
            ? '50% bulan pertama'
            : '50% grand total Rp ' . number_format($grandTotal, 0, ',', '.');
 
        return back()->with('success',
            ' Advance booking <strong>' . $booking->booking_code . '</strong> disetujui ke Waiting List posisi #' . $wl?->position . '. ' .
            'DP (' . $dpLabel . ') = ' .
            'Invoice DP <strong>Rp ' . number_format($dpAmount, 0, ',', '.') . '</strong> diterbitkan. ' .
            'Penghuni dapat membayar via menu <strong>Tagihan</strong>.'
        );
    }

    public function rejectBooking(Request $request, $id)
    {
        $booking = Booking::with('user', 'room')->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'under_review'])) {
            return back()->with('error', 'Booking ini tidak bisa ditolak (status: ' . $booking->status . ').');
        }

        $booking->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        (new NotificationService())->notifyBookingRejected($booking);

        if ($booking->booking_type === 'advance' && $booking->advance_for_user_id) {
            InAppNotification::send(
                $booking->advance_for_user_id,
                'renewal_available',
                ' Kamar Anda Bisa Diperpanjang',
                'Calon penghuni baru untuk kamar ' . $booking->room?->room_number .
                    ' tidak jadi masuk. Silakan ajukan perpanjangan dari menu Perpanjangan Sewa.',
                route('tenant.renewal.index'),
                ['room_id' => $booking->room_id]
            );
        }

        return back()->with('success',
            'Booking <strong>' . $booking->booking_code . '</strong> ditolak.'
        );
    }

    public function listWaitingList()
    {
        $waitingLists = WaitingList::with(['user', 'room', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.waiting-list.index', compact('waitingLists'));
    }

    public function showWaitingList($id)
    {
        $wl = WaitingList::with(['user', 'room', 'booking', 'approvedByUser'])
            ->findOrFail($id);

        return view('admin.waiting-list.show', compact('wl'));
    }

    public function notifyWaitingListAvailable(Request $request, $id)
    {
        $wl = WaitingList::with(['user', 'room', 'booking'])->findOrFail($id);

        if (!in_array($wl->status, ['waiting', 'notified'])) {
            return back()->with('error', 'Status waiting list tidak valid untuk aksi ini.');
        }

        $readiness = $this->resolveNotifyReadiness($wl);
        if ($readiness && !$readiness['is_ready']) {
            $tgl = $readiness['period_start']->format('d M Y');
            return back()->with('error',
                ' Belum bisa mengirim notifikasi. Tanggal check-in ' .
                '<strong>' . ($wl->user?->name ?? '-') . '</strong> ' .
                'baru dimulai pada <strong>' . $tgl . '</strong>. ' .
                'Kirim notifikasi pada atau setelah tanggal tersebut.'
            );
        }

        $notif           = new NotificationService();
        $pricePerMonth   = $wl->room?->price ?? 0;
        $duration        = $wl->booking?->duration_months ?? 1;
        $servicePerMonth = (float) ($wl->booking?->service_amount ?? 0);
        $paymentMethod   = $wl->booking?->payment_method ?? 'lump_sum';

        $bookingId = (int) ($wl->booking_id ?? $wl->booking?->id ?? 0);

        $checkInDate   = $wl->booking?->check_in_date;
        $checkInString = $checkInDate ? $checkInDate->toDateString() : now()->toDateString();
        $billingDay    = min((int) \Carbon\Carbon::parse($checkInString)->format('d'), 28);

        $grandTotal = ($pricePerMonth + $servicePerMonth) * $duration;

        $dpInvoicePaid = Invoice::where('invoice_type', 'waiting_list_dp')
            ->where('status', 'paid')
            ->where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id)
                ->orWhere('booking_id', $wl->booking_id)
                ->orWhere(function ($q2) use ($wl) {
                    $q2->where('user_id', $wl->user_id)
                        ->where('room_id', $wl->room_id);
                });
            })
            ->orderByDesc('paid_at')
            ->first();

        $dpPaidSynced = $dpInvoicePaid
            ? (int) $dpInvoicePaid->total_amount
            : (int) ($wl->dp_paid ?? 0);

        if ($dpInvoicePaid) {
            $updateData = [];
            if ($dpPaidSynced !== (int) ($wl->dp_paid ?? 0)) {
                $updateData['dp_paid']    = $dpPaidSynced;
                $updateData['dp_paid_at'] = $wl->dp_paid_at ?? $dpInvoicePaid->paid_at ?? now();
            }
            try {
                if (isset($wl->dp_invoice_id) && !$wl->dp_invoice_id) {
                    $updateData['dp_invoice_id'] = $dpInvoicePaid->id;
                }
            } catch (\Exception $e) {}

            if (!empty($updateData)) {
                $wl->update($updateData);
                $wl->refresh();
            }

            if (!$dpInvoicePaid->waiting_list_id) {
                $dpInvoicePaid->update(['waiting_list_id' => $wl->id]);
            }
        }

        if ($paymentMethod === 'monthly') {
            $sisaBayar = max(0, (int) round(($pricePerMonth + $servicePerMonth) * 0.5));
        } else {
            $sisaBayar = max(0, $grandTotal - $dpPaidSynced);
        }

        $periodStart = $checkInString;
        $periodEnd   = $paymentMethod === 'monthly'
            ? \Carbon\Carbon::parse($checkInString)->addMonth()->subDay()->toDateString()
            : \Carbon\Carbon::parse($checkInString)->addMonths($duration)->toDateString();

        DB::transaction(function () use (
            $wl, $sisaBayar, $grandTotal, $request,
            $periodStart, $periodEnd, $duration,
            $dpPaidSynced, $paymentMethod, $pricePerMonth, $servicePerMonth,
            $bookingId, $billingDay
        ) {
            $wl->room?->update(['status' => 'available']);
            $wl->update(['status' => 'notified', 'notified_at' => now()]);

            $alreadyExists = Invoice::where('waiting_list_id', $wl->id)
                ->where('invoice_type', 'waiting_list_remaining')
                ->exists();

            if (!$alreadyExists && $sisaBayar > 0) {
                $notes = $paymentMethod === 'monthly'
                    ? 'Pelunasan 50% sisa biaya bulan pertama kamar ' . $wl->room?->room_number .
                    '. Bulan ke-2 dst ditagih otomatis per bulan saat aktif. ' .
                    'DP terbayar: Rp ' . number_format($dpPaidSynced, 0, ',', '.') .
                    '. Periode bulan pertama: ' . $periodStart . ' s/d ' . $periodEnd . '.'
                    : 'Pelunasan sewa kamar ' . $wl->room?->room_number .
                    '. Grand total Rp ' . number_format($grandTotal, 0, ',', '.') .
                    ' (sewa + layanan × ' . $duration . ' bln)' .
                    ', dikurangi DP Rp ' . number_format($dpPaidSynced, 0, ',', '.') .
                    '. Sisa: Rp ' . number_format($sisaBayar, 0, ',', '.') .
                    '. Periode: ' . $periodStart . ' s/d ' . $periodEnd . '.';

                Invoice::create([
                    'user_id'         => $wl->user_id,
                    'room_id'         => $wl->room_id,
                    'booking_id'      => $wl->booking_id,
                    'waiting_list_id' => $wl->id,
                    'invoice_number'  => 'INV-SISA-' . now()->format('Ymd') . '-' . str_pad($wl->id, 4, '0', STR_PAD_LEFT),
                    'invoice_type' => 'waiting_list_remaining',
                    'invoice_type'    => 'waiting_list_remaining',
                    'base_amount'     => $sisaBayar,
                    'service_amount'  => 0,
                    'total_amount'    => $sisaBayar,
                    'tax_amount'      => 0,
                    'status'          => 'unpaid',
                    'due_date'        => now()->toDateString(),
                    'period_start'    => $periodStart,
                    'period_end'      => $periodEnd,
                    'notes'           => $notes,
                ]);
            }

            if ($paymentMethod === 'monthly' && $bookingId > 0) {
                \App\Models\Booking::where('id', $bookingId)
                    ->update([
                        'monthly_billing_day'   => $billingDay,
                        'monthly_invoice_count' => 1, 
                    ]);

                Log::info('[AdminController] notifyWaitingListAvailable: set monthly fields booking #' . $bookingId . 
                    ' billing_day=' . $billingDay . ' invoice_count=1');
            }
        });

        (new NotificationService())->notifyRoomAvailableForWaitingList($wl->fresh());

        $msgSisa = $sisaBayar > 0
            ? ($paymentMethod === 'monthly'
                ? 'Invoice pelunasan 50% bulan pertama <strong>Rp ' . number_format($sisaBayar, 0, ',', '.') . '</strong> diterbitkan. Bulan ke-2 dst ditagih otomatis.'
                : 'Invoice pelunasan <strong>Rp ' . number_format($sisaBayar, 0, ',', '.') . '</strong> diterbitkan.')
            : ' DP sudah mencakup penuh. Tidak ada invoice sisa.';

        return back()->with('success',
            ' Notifikasi dikirim ke <strong>' . $wl->user?->name . '</strong>. ' . $msgSisa
        );
    }

    public function waivePenalty(Request $request, $id)
    {
        $wl = WaitingList::findOrFail($id);

        $wl->update([
            'penalty_waived'      => true,
            'penalty_waived_by'   => auth()->id(),
            'penalty_waived_at'   => now(),
            'penalty_waived_note' => $request->note,
        ]);

        return back()->with('success', 'Denda waiting list berhasil dibebaskan.');
    }

    public function listInvoices()
    {
        $invoices = Invoice::with(['user', 'room', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function createInvoice()
    {
        $tenants = User::whereHas('role', fn($q) => $q->whereIn('name', ['tenant', 'prospective_tenant']))
            ->where('is_active', true)
            ->with(['bookings' => fn($q) => $q->whereIn('status', ['approved', 'active', 'waiting_list'])->with('room')])
            ->orderBy('name')
            ->get();

        return view('admin.invoices.create', compact('tenants'));
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'room_id'        => 'required|exists:rooms,id',
            'base_amount'    => 'required|numeric|min:0',
            'service_amount' => 'nullable|numeric|min:0',
            'period_start'   => 'required|date',
            'period_end'     => 'required|date|after_or_equal:period_start',
            'due_date'       => 'required|date',
            'notes'          => 'nullable|string|max:500',
        ]);

        $baseAmount    = (float) $request->base_amount;
        $serviceAmount = (float) ($request->service_amount ?? 0);

        $invoice = Invoice::create([
            'user_id'        => $request->user_id,
            'room_id'        => $request->room_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'base_amount'    => $baseAmount,
            'service_amount' => $serviceAmount,
            'total_amount'   => $baseAmount + $serviceAmount,
            'period_start'   => $request->period_start,
            'period_end'     => $request->period_end,
            'due_date'       => $request->due_date,
            'status'         => 'unpaid',
            'notes'          => $request->notes,
        ]);

        (new NotificationService())->notifyInvoiceCreated($invoice);

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', ' Invoice <strong>' . $invoice->invoice_number . '</strong> berhasil dibuat.');
    }

    public function showInvoice($id)
    {
        $invoice = Invoice::with(['user', 'room', 'booking', 'payments'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function verifyInvoicePayment(Request $request, $invoiceId)
    {
        $invoice = Invoice::with(['user.role', 'room', 'booking', 'waitingList'])->findOrFail($invoiceId);
        $notif   = new NotificationService();

        if ($invoice->status === 'paid') {
            return back()->with('info', 'Invoice ini sudah berstatus lunas.');
        }

        $invoice->update([
            'status'      => 'paid',
            'paid_at'     => now(),
            'verified_by' => auth()->id(),
            'notes'       => $request->filled('notes') ? $request->notes : $invoice->notes,
        ]);

        $invoiceNum     = $invoice->invoice_number;
        $invoiceType    = $invoice->invoice_type ?? '';
        $isDP           = str_starts_with($invoiceNum, 'INV-DP-')   || $invoiceType === 'waiting_list_dp';
        $isSisa         = str_starts_with($invoiceNum, 'INV-SISA-') || $invoiceType === 'waiting_list_remaining';
        $isMonthlyFirst = $invoiceType === 'monthly_first';
        $isMonthly      = $invoiceType === 'monthly';

        $wl = $this->lookupWaitingList($invoice);

        $checkIn    = $invoice->booking?->check_in_date
                    ?? $invoice->period_start
                    ?? now();
        $billingDay = min((int) \Carbon\Carbon::parse($checkIn)->format('d'), 28);

        if ($wl && !$invoice->waiting_list_id) {
            $invoice->update(['waiting_list_id' => $wl->id]);
        }

        if ($isDP) {
            InAppNotification::send(
                $invoice->user_id,
                'dp_payment_verified',
                ' DP Waiting List Diterima',
                'DP Anda sebesar Rp ' . number_format($invoice->total_amount, 0, ',', '.') .
                    ' untuk kamar ' . $invoice->room?->room_number . ' sudah diterima. Posisi antrian Anda aman.',
                route('prospective.invoices.index')
            );

            return back()->with('success',
                ' DP <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
                'Calon penghuni telah dinotifikasi.'
            );
        }

        if ($isSisa) {
            $invoice->refresh(); 
            $invoice->load(['user.role', 'room', 'booking.room', 'waitingList.booking']);
            return $this->activateNewTenantFromWaitingList($invoice, $wl, $notif);
        }

        if ($isMonthlyFirst) {
            $user     = $invoice->user;
            $upgraded = false;

            $invoice->room?->update(['status' => 'occupied']);

            if ($invoice->booking) {
                $bookingUpdate = ['status' => 'active'];

                if (empty($invoice->booking->check_in_date)) {
                    $bookingUpdate['check_in_date'] = now()->toDateString();
                }

                $paymentMethod = $invoice->booking->payment_method ?? 'lump_sum';
                if ($paymentMethod === 'monthly') {
                    $bookingUpdate['monthly_billing_day']   = $billingDay;
                    $bookingUpdate['monthly_invoice_count'] = 1;
                }

                $invoice->booking->update($bookingUpdate);
                $invoice->booking->refresh(); 
            }

            if ($user && $user->role?->name === 'prospective_tenant') {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);
                $notif->notifyTenantUpgraded($user, $invoice->room);
                $upgraded = true;
            }

            $notif->notifyInvoicePaid($invoice);

            InAppNotification::send(
                $invoice->user_id,
                'monthly_first_paid',
                ' Pembayaran Bulan Pertama Dikonfirmasi!',
                'Anda resmi menghuni kamar ' . $invoice->room?->room_number . '. ' .
                    'Tagihan bulan berikutnya akan dikirim otomatis sebelum tanggal ' . $billingDay . ' tiap bulan.',
                route('tenant.invoices.index'),
                ['invoice_id' => $invoice->id]
            );

            $msg = ' Invoice bulan pertama <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
                'Kamar <strong>' . $invoice->room?->room_number . '</strong> → Occupied. ' .
                'Invoice bulanan berikutnya akan dibuat otomatis oleh sistem.';

            if ($upgraded) {
                $msg .= ' <strong>' . $user->name . '</strong> diupgrade menjadi Penghuni Aktif.';
            }

            return back()->with('success', $msg);
        }

        if ($isMonthly) {
            $booking = $invoice->booking;

            if ($booking) {
                $monthNumber = $invoice->month_number ?? 0;

                if ($monthNumber > ($booking->monthly_invoice_count ?? 0)) {
                    $booking->update(['monthly_invoice_count' => $monthNumber]);
                }

                $isLastMonth = ($monthNumber >= $booking->duration_months);

                $nextPeriodStart = \Carbon\Carbon::parse($booking->check_in_date)
                    ->addMonths($monthNumber)
                    ->startOfDay();
                $isHandedToRenewal = $booking->renewal_id
                    && $booking->renewal_payment_method !== 'monthly'
                    && $booking->renewal_period_start
                    && $nextPeriodStart->gte(\Carbon\Carbon::parse($booking->renewal_period_start)->startOfDay());

                if ($isLastMonth || $isHandedToRenewal) {
                    $booking->update([
                        'status'   => 'completed',
                        'end_date' => $invoice->period_end?->toDateString() ?? now()->toDateString(),
                    ]);

                    InAppNotification::send(
                        $invoice->user_id,
                        'monthly_last_paid',
                        ' Pelunasan Akhir Dikonfirmasi — Masa Sewa Selesai',
                        'Pembayaran bulan terakhir untuk kamar ' . $invoice->room?->room_number .
                            ' dikonfirmasi. Masa sewa Anda resmi berakhir pada ' .
                            $invoice->period_end?->format('d M Y') . '.',
                        route('tenant.invoices.show', $invoice->id)
                    );
                } else {
                    InAppNotification::send(
                        $invoice->user_id,
                        'monthly_invoice_paid',
                        ' Tagihan Bulan ke-' . $monthNumber . ' Dikonfirmasi',
                        'Tagihan bulan ke-' . $monthNumber . ' untuk kamar ' . $invoice->room?->room_number .
                            ' telah lunas. Tagihan bulan berikutnya akan dikirim otomatis.',
                        route('tenant.invoices.show', $invoice->id)
                    );
                }
            }

            $notif->notifyInvoicePaid($invoice);

            $isLast = ($invoice->month_number ?? 0) >= ($booking?->duration_months ?? 0);

            return back()->with('success',
                ' Invoice bulanan <strong>' . $invoice->invoice_number . '</strong> ' .
                '(bulan ke-' . $invoice->month_number . ') lunas.' .
                ($isLast
                    ? ' Masa sewa booking ini telah <strong>selesai</strong>.'
                    : ' Tagihan bulan berikutnya otomatis dibuat oleh sistem.')
            );
        }
        
        $isRenewal = $invoiceType === 'renewal' || str_starts_with($invoiceNum, 'INV-RNW-');

        if ($isRenewal) {
            $renewal           = \App\Models\Renewal::find($invoice->renewal_id);
            $bookingForRenewal = $renewal
                ? \App\Models\Booking::find($renewal->booking_id)
                : null;

            if (!$bookingForRenewal) {
                $bookingForRenewal = \App\Models\Booking::where('user_id', $invoice->user_id)
                    ->where('room_id', $invoice->room_id)
                    ->whereIn('status', ['approved', 'active'])
                    ->latest()->first();
            }

            if ($bookingForRenewal
                && $bookingForRenewal->payment_method === 'monthly'
                && ($bookingForRenewal->monthly_billing_day === null
                    || ($bookingForRenewal->monthly_invoice_count ?? 0) == 0)
            ) {
                $checkInRenewal = $bookingForRenewal->check_in_date ?? now();
                $bdRenewal      = min((int) \Carbon\Carbon::parse($checkInRenewal)->format('d'), 28);
                $bookingForRenewal->update([
                    'monthly_billing_day'   => $bdRenewal,
                    'monthly_invoice_count' => max(1, $bookingForRenewal->monthly_invoice_count ?? 0),
                ]);
            }

            $notif->notifyInvoicePaid($invoice);

            return back()->with('success',
                ' Invoice renewal <strong>' . $invoice->invoice_number . '</strong> lunas.'
            );
        }

        $invoice->room?->update(['status' => 'occupied']);

        $user     = $invoice->user;
        $upgraded = false;

        if ($user && $user->role?->name === 'prospective_tenant') {
            $tenantRole = Role::where('name', 'tenant')->firstOrFail();
            $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
            LoginController::generateAndSendOtp($user->email, 'login', $user->name);
            $notif->notifyTenantUpgraded($user, $invoice->room);
            $upgraded = true;
        }

        if ($invoice->booking && $invoice->booking->status === 'approved') {
            $bookingUpdate = ['status' => 'active'];

            if (($invoice->booking->payment_method ?? 'lump_sum') === 'monthly'
                && ($invoice->booking->monthly_invoice_count ?? 0) === 0) {
                $checkIn    = $invoice->booking->check_in_date ?? now();
                $billingDay = min((int) \Carbon\Carbon::parse($checkIn)->format('d'), 28);
                $bookingUpdate['monthly_billing_day']   = $billingDay;
                $bookingUpdate['monthly_invoice_count'] = 1;
            }

            $invoice->booking->update($bookingUpdate);
        }

        $notif->notifyInvoicePaid($invoice);

        $msg = ' Invoice <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
            'Kamar <strong>' . $invoice->room?->room_number . '</strong> → Occupied.';

        if ($upgraded) {
            $msg .= ' <strong>' . $user->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }

        return back()->with('success', $msg);
    }
    
    private function activateNewTenantFromWaitingList(Invoice $invoice, ?WaitingList $wl, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $user          = $invoice->user;
        $roomId        = $invoice->room_id;
        $upgraded      = false;
        $oldTenantName = null;

        $oldTenantUserId = $wl?->booking?->advance_for_user_id
            ?? $invoice->booking?->advance_for_user_id;

        $booking = null;
        $bookingId = $invoice->booking_id 
            ?? $wl?->booking_id 
            ?? null;

        if ($bookingId) {
            $booking = \App\Models\Booking::with(['room'])->find($bookingId);
        }

        if (!$booking && $invoice->booking_id) {
            $invoice->loadMissing('booking');
            $booking = $invoice->booking?->fresh();
            $booking?->loadMissing(['room']);
        }

        if (!$booking && $wl?->booking_id) {
            $wl->loadMissing('booking');
            $booking = $wl->booking?->fresh();
            $booking?->loadMissing(['room']);
        }

        if (!$booking && $invoice->user_id && $invoice->room_id) {
            $booking = \App\Models\Booking::with(['room'])
                ->where('user_id', $invoice->user_id)
                ->where('room_id', $invoice->room_id)
                ->whereIn('status', ['waiting_list', 'pending', 'approved', 'active'])
                ->latest()
                ->first();
        }

        $paymentMethodOuter = $booking?->payment_method ?? 'lump_sum';
        $checkInSource      = $booking?->check_in_date ?? $invoice->period_start ?? now();
        $billingDayOuter    = min((int) \Carbon\Carbon::parse($checkInSource)->format('d'), 28);

        DB::transaction(function () use (
            $invoice, $wl, $user, $roomId,
            $oldTenantUserId, &$upgraded, &$oldTenantName,
            $notif, $booking, $paymentMethodOuter, $billingDayOuter
        ) {
            $oldBookings = Booking::where('room_id', $roomId)
                ->whereIn('status', ['approved', 'active'])
                ->where('user_id', '!=', $user->id)
                ->with('user.role')
                ->get();

            foreach ($oldBookings as $oldBooking) {
                $oldTenantUser = $oldBooking->user;

                $oldBooking->update([
                    'status'   => 'completed',
                    'end_date' => now()->toDateString(),
                ]);

                Log::info('[AdminController] Booking lama #' . $oldBooking->id .
                    ' (' . ($oldTenantUser?->name ?? '-') . ') → completed.');

                if ($oldTenantUser) {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUser->id)
                        ->whereIn('status', ['approved', 'active'])
                        ->where('id', '!=', $oldBooking->id)
                        ->exists();

                    if (!$stillHasRoom && $oldTenantUser->role?->name === 'tenant') {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first()
                            ?? Role::where('name', 'ex_tenant')->first();

                        if ($prospectiveRole) {
                            $oldTenantUser->update(['role_id' => $prospectiveRole->id]);
                        }
                        $oldTenantName = $oldTenantUser->name;

                        InAppNotification::send(
                            $oldTenantUser->id,
                            'tenancy_ended',
                            ' Masa Sewa Kamar Telah Berakhir',
                            'Masa sewa kamar ' . ($invoice->room?->room_number ?? '-') .
                                ' Anda telah resmi berakhir dan diserahterimakan ke penghuni baru.',
                            route('home')
                        );

                        try {
                            $notif->notifyTenancyEnded($oldTenantUser, $invoice->room);
                        } catch (\Exception $e) {
                            Log::warning('[AdminController] notifyTenancyEnded gagal: ' . $e->getMessage());
                        }
                    }
                }
            }

            if ($oldTenantUserId && !$oldBookings->firstWhere('user_id', $oldTenantUserId)) {
                $directOldTenant = User::with('role')->find($oldTenantUserId);
                if ($directOldTenant && $directOldTenant->role?->name === 'tenant') {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUserId)
                        ->whereIn('status', ['approved', 'active'])
                        ->exists();

                    if (!$stillHasRoom) {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first();
                        if ($prospectiveRole) {
                            $directOldTenant->update(['role_id' => $prospectiveRole->id]);
                        }
                        $oldTenantName = $oldTenantName ?? $directOldTenant->name;
                    }
                }
            }

            if ($user && in_array($user->role?->name, ['prospective_tenant', 'prospective'])) {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);

                try {
                    $notif->notifyTenantUpgraded($user, $invoice->room);
                } catch (\Exception $e) {
                    Log::warning('[AdminController] notifyTenantUpgraded gagal: ' . $e->getMessage());
                }

                $upgraded = true;
            }

            if ($booking) {
                $bookingUpdate = [
                    'status'  => 'active',
                    'room_id' => $roomId,
                ];

                if (empty($booking->check_in_date)) {
                    $bookingUpdate['check_in_date'] = now()->toDateString();
                }

                if ($paymentMethodOuter === 'monthly') {
                    $bookingUpdate['monthly_billing_day']   = $billingDayOuter;
                    $bookingUpdate['monthly_invoice_count'] = 1;
                }

                $affected = \App\Models\Booking::where('id', $booking->id)
                    ->update($bookingUpdate);

                Log::info('[AdminController] Booking #' . $booking->id .
                    ' → active. Rows affected: ' . $affected .
                    '. PaymentMethod: ' . $paymentMethodOuter);
            } else {
                Log::error('[AdminController] activateNewTenantFromWaitingList: booking null!', [
                    'invoice_id'  => $invoice->id,
                    'booking_id'  => $invoice->booking_id,
                    'wl_id'       => $wl?->id,
                ]);
            }

            WaitingList::where('room_id', $roomId)
                ->whereIn('status', ['waiting', 'notified'])
                ->update([
                    'status'       => 'converted',
                    'converted_at' => now(),
                ]);

            if ($wl && !$invoice->waiting_list_id) {
                $invoice->update(['waiting_list_id' => $wl->id]);
            }

            $invoice->room?->update(['status' => 'occupied']);
        });

        if ($oldTenantUserId) {
            InAppNotification::send(
                $oldTenantUserId,
                'room_handover_complete',
                ' Penghuni Baru Sudah Aktif',
                'Kamar ' . ($invoice->room?->room_number ?? '-') . ' kini resmi ditempati penghuni baru.',
                route('home')
            );

            try {
                $notif->notifyOldTenantRoomHandover($oldTenantUserId, $invoice->room);
            } catch (\Exception $e) {
                Log::warning('[AdminController] notifyOldTenantRoomHandover gagal: ' . $e->getMessage());
            }
        }

        InAppNotification::send(
            $invoice->user_id,
            'tenancy_activated',
            ' Selamat! Anda Resmi Menjadi Penghuni',
            'Pelunasan untuk kamar ' . ($invoice->room?->room_number ?? '-') .
                ' dikonfirmasi. Hubungi admin untuk serah terima kunci.',
            route('tenant.dashboard')
        );

        $msg = ' Pelunasan <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
            'Kamar <strong>' . ($invoice->room?->room_number ?? '-') . '</strong> → Occupied.';

        if ($upgraded) {
            $msg .= ' <strong>' . $invoice->user?->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }
        if ($oldTenantName) {
            $msg .= ' Penghuni lama <strong>' . $oldTenantName . '</strong> telah diselesaikan statusnya.';
        }

        $bookingFresh  = \App\Models\Booking::find($booking?->id);
        $paymentMethod = $bookingFresh?->payment_method ?? 'lump_sum';

        if ($paymentMethod === 'monthly') {
            $billingDay = $bookingFresh?->monthly_billing_day ?? $billingDayOuter;
            InAppNotification::send(
                $invoice->user_id,
                'monthly_schedule_info',
                ' Jadwal Tagihan Bulanan Anda',
                'Tagihan bulan ke-2 dan seterusnya akan dikirim otomatis setiap tanggal ' .
                    $billingDay . ' tiap bulan.',
                route('tenant.invoices.index'),
                ['booking_id' => $invoice->booking_id]
            );
            $msg .= ' Tagihan bulanan otomatis setiap tanggal <strong>' . $billingDay . '</strong>.';
        }

        return back()->with('success', $msg);
    }

    private function lookupWaitingList(Invoice $invoice): ?WaitingList
    {
        if ($invoice->waiting_list_id) {
            $wl = WaitingList::find($invoice->waiting_list_id);
            if ($wl) return $wl;
        }

        if ($invoice->booking_id) {
            $wl = WaitingList::where('booking_id', $invoice->booking_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()
                ->first();
            if ($wl) return $wl;
        }

        if ($invoice->user_id && $invoice->room_id
            && in_array($invoice->type, ['waiting_list_dp', 'waiting_list_remaining'])) {
            $wl = WaitingList::where('user_id', $invoice->user_id)
                ->where('room_id', $invoice->room_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()
                ->first();
            if ($wl) return $wl;
        }

        return null;
    }

    private function resolveNotifyReadiness(WaitingList $wl): ?array
    {
        $dpInvoice = Invoice::where('invoice_type', 'waiting_list_dp')
            ->where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id)
                  ->orWhere('booking_id', $wl->booking_id)
                  ->orWhere(function ($q2) use ($wl) {
                      $q2->where('user_id', $wl->user_id)
                         ->where('room_id', $wl->room_id);
                  });
            })
            ->latest()
            ->first();

        $periodStart = null;
        if ($dpInvoice?->period_start) {
            $periodStart = \Carbon\Carbon::parse($dpInvoice->period_start);
        } elseif ($wl->booking?->check_in_date) {
            $periodStart = \Carbon\Carbon::parse($wl->booking->check_in_date);
        }

        if (!$periodStart) {
            return null;
        }

        return [
            'is_ready'     => now()->gte($periodStart),
            'period_start' => $periodStart,
        ];
    }

    private function createInvoiceForBooking(Booking $booking): Invoice
    {
        $pricePerMonth   = $booking->room->price ?? 0;
        $duration        = $booking->duration_months ?? 1;
        $servicePerMonth = (float) ($booking->service_amount ?? 0);
        $paymentMethod   = $booking->payment_method ?? 'lump_sum';
    
        $serviceNote = '';
        if ($servicePerMonth > 0 && !empty($booking->additional_services)) {
            $lines = [];
            foreach ($booking->additional_services as $key => $svc) {
                $lines[] = ($svc['label'] ?? $key) . ' (Rp ' . number_format($svc['price'] ?? 0, 0, ',', '.') . '/bln)';
            }
            $serviceNote = ' Layanan tambahan: ' . implode(', ', $lines) . '.';
        }
    
        if ($paymentMethod === 'monthly') {
            $baseAmount    = $pricePerMonth;
            $serviceAmount = $servicePerMonth;
            $totalAmount   = $baseAmount + $serviceAmount;
    
            $periodStart = $booking->check_in_date;
            $periodEnd   = $booking->check_in_date?->copy()->addMonth()->subDay();
            $dueDate     = $booking->check_in_date ?? now();
    
            return Invoice::create([
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'booking_id'     => $booking->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_type'   => 'monthly_first',
                'month_number'   => 1,
                'base_amount'    => $baseAmount,
                'service_amount' => $serviceAmount,
                'total_amount'   => $totalAmount,
                'period_start'   => $periodStart,
                'period_end'     => $periodEnd,
                'due_date'       => $dueDate,
                'status'         => 'unpaid',
                'notes'          => 'Invoice sewa kamar ' . ($booking->room?->room_number ?? '') .
                                    ' bulan ke-1 dari ' . $duration . ' bulan (Bayar Bulanan).' .
                                    $serviceNote .
                                    ' Bayar untuk mengkonfirmasi hunian Anda.',
            ]);
    
        } else {
            $baseAmount    = $pricePerMonth * $duration;
            $serviceAmount = $servicePerMonth * $duration;
            $totalAmount   = $baseAmount + $serviceAmount;
            $dueDate       = now()->addDays(7);
    
            return Invoice::create([
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'booking_id'     => $booking->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_type'   => 'booking',
                'month_number'   => null,
                'base_amount'    => $baseAmount,
                'service_amount' => $serviceAmount,
                'total_amount'   => $totalAmount,
                'period_start'   => $booking->check_in_date,
                'period_end'     => $booking->check_in_date?->copy()->addMonths($duration)->subDay(),
                'due_date'       => $dueDate,
                'status'         => 'unpaid',
                'notes'          => 'Invoice booking kamar ' . ($booking->room?->room_number ?? '') .
                                    ' selama ' . $duration . ' bulan (Bayar Lunas).' .
                                    $serviceNote .
                                    ' Bayar untuk mengkonfirmasi hunian Anda.',
            ]);
        }
    }

    private function generateInvoiceNumber(): string
    {
        return 'INV-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(6));
    }

    public function downgradeTenant(Request $request, $id)
    {
        $tenant = User::findOrFail($id);

        abort_if($tenant->role?->name !== 'tenant', 422, 'User bukan penghuni aktif.');

        $prospectiveRole = \App\Models\Role::where('name', 'prospective_tenant')->firstOrFail();

        $activeBooking = \App\Models\Booking::with('room')
            ->where('user_id', $tenant->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->first();

        DB::transaction(function () use ($tenant, $prospectiveRole, $activeBooking, $request) {
            $tenant->update(['role_id' => $prospectiveRole->id]);

            if ($activeBooking) {
                $activeBooking->update([
                    'status'      => 'expired',
                    'admin_notes' => $request->notes
                        ? 'Diturunkan admin: ' . $request->notes
                        : 'Diturunkan ke calon penghuni oleh admin.',
                ]);

                $activeBooking->room?->update(['status' => 'available']);
            }
        });

        InAppNotification::send(
            $tenant->id,
            'account_downgraded',
            ' Status Akun Diperbarui',
            'Status akun Anda telah diubah menjadi Calon Penghuni. Hubungi admin jika ada pertanyaan.',
            null,
            []
        );

        return back()->with(
            'success',
            ' <strong>' . $tenant->name . '</strong> berhasil diturunkan ke Calon Penghuni. ' .
            ($activeBooking ? 'Kamar <strong>' . $activeBooking->room?->room_number . '</strong> kembali tersedia.' : '')
        );
    }

    public function deactivateTenant($id)
    {
        $tenant = User::findOrFail($id);
        $tenant->update(['is_active' => false]);

        return back()->with('success', ' Akun <strong>' . $tenant->name . '</strong> berhasil dinonaktifkan.');
    }

    public function activateTenantAccount($id)
    {
        $tenant = User::findOrFail($id);
        $tenant->update(['is_active' => true]);

        return back()->with('success', ' Akun <strong>' . $tenant->name . '</strong> berhasil diaktifkan kembali.');
    }
}
