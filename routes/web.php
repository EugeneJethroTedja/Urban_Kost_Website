<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Tenant\TenantController;
use App\Http\Controllers\Tenant\RenewalController;
use App\Http\Controllers\Tenant\InvoiceController as TenantInvoiceController;
use App\Http\Controllers\Prospective\WaitingListController as ProspectiveWaitingListController;
use App\Http\Controllers\Prospective\InvoiceController as ProspectiveInvoiceController;
use App\Http\Controllers\Admin\ServiceCatalogController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{room}', [HomeController::class, 'showRoom'])->name('rooms.show');
Route::post('/chatbot', [ChatbotController::class, 'respond'])->name('chatbot.respond');

// AUTH
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/send-otp', [LoginController::class, 'sendOtp'])->name('login.send-otp');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// OTP
Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify.submit');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

// Logout
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'otp.verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Booking
Route::get('/booking/{room_id}', [BookingController::class, 'create'])->name('booking.create');
Route::middleware(['auth', 'otp.verified'])->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// Notification
Route::middleware(['auth', 'otp.verified'])
    ->prefix('notifikasi')
    ->name('notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
    });

// Redirect Login
Route::get('/redirect', function () {
    return redirect()->route(auth()->user()->getDashboardRoute());
})->middleware(['auth', 'otp.verified'])->name('dashboard');

// Owner
Route::middleware(['auth', 'otp.verified', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

        Route::get('/admins', [OwnerController::class, 'listAdmins'])->name('admins.index');
        Route::get('/admins/create', [OwnerController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins', [OwnerController::class, 'storeAdmin'])->name('admins.store');
        Route::post('/admins/{id}/toggle', [OwnerController::class, 'toggleAdmin'])->name('admins.toggle');
        Route::delete('/admins/{id}', [OwnerController::class, 'destroyAdmin'])->name('admins.destroy');
        Route::get('/admins/{id}/edit', [OwnerController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{id}', [OwnerController::class, 'updateAdmin'])->name('admins.update');

        Route::get('/pajak', [OwnerController::class, 'tax'])->name('tax.index');
        Route::get('/pajak/export', [OwnerController::class, 'exportTax'])->name('tax.export');

        Route::get('/analitik', [OwnerController::class, 'analytics'])->name('analytics');
        Route::get('/analitik/export/{section}', [OwnerController::class, 'exportAnalytics'])
            ->name('analytics.export')
            ->where('section', 'pendapatan|hunian|keterlambatan|tiket|perpanjangan|booking');
        
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications');
    });

// Admin
Route::middleware(['auth', 'otp.verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/rooms', [AdminController::class, 'listRooms'])->name('rooms.index');
        Route::get('/rooms/create', [AdminController::class, 'createRoom'])->name('rooms.create');
        Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
        Route::get('/rooms/{id}/edit', [AdminController::class, 'editRoom'])->name('rooms.edit');
        Route::put('/rooms/{id}', [AdminController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{id}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');

        Route::delete('/rooms/{room}/images/{image}', [AdminController::class, 'destroyRoomImage'])->name('rooms.images.destroy');
        Route::patch('/rooms/{room}/images/{image}/primary', [AdminController::class, 'setRoomImagePrimary'])->name('rooms.images.primary');
        Route::post('/rooms/{room}/images/reorder', [AdminController::class, 'reorderRoomImages'])->name('rooms.images.reorder');

        Route::get('/tenants',           [AdminController::class, 'listTenants'])  ->name('tenants.index');
        Route::get('/tenants/create',    [AdminController::class, 'createTenant']) ->name('tenants.create');
        Route::post('/tenants',          [AdminController::class, 'storeTenant'])  ->name('tenants.store');
        Route::get('/tenants/{id}/edit', [AdminController::class, 'editTenant'])   ->name('tenants.edit');
        Route::put('/tenants/{id}',      [AdminController::class, 'updateTenant']) ->name('tenants.update');
        Route::delete('/tenants/{id}',   [AdminController::class, 'destroyTenant'])->name('tenants.destroy');
        Route::get('/tenants/{id}',      [AdminController::class, 'showTenant'])   ->name('tenants.show');
        Route::post('/tenants/{id}/upgrade',              [AdminController::class, 'upgradeTenant'])       ->name('tenants.upgrade');
        Route::patch('/tenants/{id}/downgrade',           [AdminController::class, 'downgradeTenant'])     ->name('tenants.downgrade');
        Route::patch('/tenants/{id}/deactivate',          [AdminController::class, 'deactivateTenant'])    ->name('tenants.deactivate');
        Route::patch('/tenants/{id}/activate-account',    [AdminController::class, 'activateTenantAccount'])->name('tenants.activate');

        Route::get('/bookings',                         [AdminController::class, 'listBookings'])          ->name('bookings.index');
        Route::get('/bookings/manual/create',           [AdminController::class, 'createManualBooking'])   ->name('bookings.manual.create');
        Route::post('/bookings/manual',                 [AdminController::class, 'storeManualBooking'])    ->name('bookings.manual.store');
        Route::get('/bookings/{id}',                    [AdminController::class, 'showBooking'])           ->name('bookings.show');
        Route::post('/bookings/{id}/approve',           [AdminController::class, 'approveBooking'])        ->name('bookings.approve');
        Route::post('/bookings/{id}/approve-waiting-list',[AdminController::class, 'approveToWaitingList'])->name('bookings.approve-waiting-list');
        Route::post('/bookings/{id}/reject',            [AdminController::class, 'rejectBooking'])         ->name('bookings.reject');
        Route::get('/bookings/{id}/edit',               [AdminController::class, 'editBooking'])           ->name('bookings.edit');
        Route::put('/bookings/{id}',                    [AdminController::class, 'updateBooking'])         ->name('bookings.update');
        Route::delete('/bookings/{id}',                 [AdminController::class, 'destroyBooking'])        ->name('bookings.destroy');

        Route::prefix('waiting-list')->name('waiting-list.')->group(function () {
            Route::get('/',                [\App\Http\Controllers\Admin\WaitingListController::class, 'index'])         ->name('index');
            Route::get('/{id}',            [\App\Http\Controllers\Admin\WaitingListController::class, 'show'])          ->name('show');
            Route::post('/{id}/notify-available', [\App\Http\Controllers\Admin\WaitingListController::class, 'notifyAvailable'])->name('notify-available');
            Route::post('/{id}/waive-penalty',    [\App\Http\Controllers\Admin\WaitingListController::class, 'waivePenalty'])   ->name('waive-penalty');
        });

        Route::get('/invoices',           [AdminInvoiceController::class, 'index'])  ->name('invoices.index');
        Route::get('/invoices/create',    [AdminInvoiceController::class, 'create']) ->name('invoices.create');
        Route::post('/invoices',          [AdminInvoiceController::class, 'store'])  ->name('invoices.store');
        Route::get('/invoices/{id}',      [AdminInvoiceController::class, 'show'])   ->name('invoices.show');
        Route::get('/invoices/{id}/edit', [AdminInvoiceController::class, 'edit'])   ->name('invoices.edit');
        Route::put('/invoices/{id}',      [AdminInvoiceController::class, 'update']) ->name('invoices.update');
        Route::delete('/invoices/{id}',   [AdminInvoiceController::class, 'destroy'])->name('invoices.destroy');

        Route::post('/invoices/{invoice}/verify',          [AdminController::class, 'verifyInvoicePayment'])          ->name('invoices.verify');
        Route::post('/invoices/{invoice}/activate-tenant', [ProspectiveInvoiceController::class, 'activateTenant'])   ->name('invoices.activate-tenant');

        Route::post('/payments/{id}/verify', [AdminInvoiceController::class, 'verifyPayment'])->name('payments.verify');
        Route::post('/payments/{id}/reject', [AdminInvoiceController::class, 'rejectPayment'])->name('payments.reject');

        Route::prefix('renewals')->name('renewals.')->group(function () {
            Route::get('/',            [\App\Http\Controllers\Admin\RenewalController::class, 'index'])    ->name('index');
            Route::get('/{id}',        [\App\Http\Controllers\Admin\RenewalController::class, 'show'])     ->name('show');
            Route::post('/{id}/approve',    [\App\Http\Controllers\Admin\RenewalController::class, 'approve'])   ->name('approve');
            Route::post('/{id}/reject',     [\App\Http\Controllers\Admin\RenewalController::class, 'reject'])    ->name('reject');
            Route::post('/{id}/send-offer', [\App\Http\Controllers\Admin\RenewalController::class, 'sendOffer'])->name('send-offer');
        });

        Route::get('/tickets',               [\App\Http\Controllers\Admin\TicketController::class, 'index'])  ->name('tickets.index');
        Route::get('/tickets/{id}',          [\App\Http\Controllers\Admin\TicketController::class, 'show'])   ->name('tickets.show');
        Route::post('/tickets/{id}/reply',   [\App\Http\Controllers\Admin\TicketController::class, 'reply'])  ->name('tickets.reply');
        Route::post('/tickets/{id}/resolve', [\App\Http\Controllers\Admin\TicketController::class, 'resolve'])->name('tickets.resolve');
        Route::post('/tickets/{id}/assign',  [\App\Http\Controllers\Admin\TicketController::class, 'assign']) ->name('tickets.assign');

        Route::get('/laporan',          [\App\Http\Controllers\Admin\ReportController::class, 'index'])  ->name('reports.index');
        Route::get('/laporan/export/{section}', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
            ->name('reports.export')
            ->where('section', 'pendapatan|booking|keterlambatan|tiket');

        Route::prefix('service-catalog')->name('service-catalog.')->group(function () {
            Route::get('/',              [ServiceCatalogController::class, 'index'])   ->name('index');
            Route::get('/create',        [ServiceCatalogController::class, 'create'])  ->name('create');
            Route::post('/',             [ServiceCatalogController::class, 'store'])   ->name('store');
            Route::get('/{id}',          [ServiceCatalogController::class, 'show'])    ->name('show');
            Route::get('/{id}/edit',     [ServiceCatalogController::class, 'edit'])    ->name('edit');
            Route::put('/{id}',          [ServiceCatalogController::class, 'update'])  ->name('update');
            Route::delete('/{id}',       [ServiceCatalogController::class, 'destroy']) ->name('destroy');
            Route::patch('/{id}/toggle', [ServiceCatalogController::class, 'toggle'])  ->name('toggle');
            Route::post('/reorder',      [ServiceCatalogController::class, 'reorder']) ->name('reorder');
        });
    });

// Penghuni Aktif
Route::middleware(['auth', 'otp.verified', 'role:tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('dashboard');
        Route::get('/bookings', [TenantController::class, 'myBookings'])->name('bookings');

        Route::get('/tagihan',               [TenantInvoiceController::class, 'index'])       ->name('invoices.index');
        Route::get('/tagihan/{id}',          [TenantInvoiceController::class, 'show'])        ->name('invoices.show');
        Route::post('/tagihan/{id}/bayar',   [TenantInvoiceController::class, 'pay'])         ->name('invoices.pay');
        Route::get('/tagihan/{id}/midtrans', [TenantInvoiceController::class, 'payMidtrans']) ->name('invoices.pay-midtrans');
        Route::post('/tagihan/{id}/snap-token', [TenantInvoiceController::class, 'snapToken'])->name('invoices.snap-token');

        Route::get('/tiket',             [\App\Http\Controllers\Tenant\TicketController::class, 'index'])  ->name('tickets.index');
        Route::get('/tiket/buat',        [\App\Http\Controllers\Tenant\TicketController::class, 'create']) ->name('tickets.create');
        Route::post('/tiket',            [\App\Http\Controllers\Tenant\TicketController::class, 'store'])  ->name('tickets.store');
        Route::get('/tiket/{id}',        [\App\Http\Controllers\Tenant\TicketController::class, 'show'])   ->name('tickets.show');
        Route::post('/tiket/{id}/balas', [\App\Http\Controllers\Tenant\TicketController::class, 'reply'])  ->name('tickets.reply');
        Route::post('/tiket/{id}/tutup', [\App\Http\Controllers\Tenant\TicketController::class, 'close'])  ->name('tickets.close');
        Route::post('/tiket/{id}/nilai', [\App\Http\Controllers\Tenant\TicketController::class, 'rate'])   ->name('tickets.rate');
        Route::get('/tiket/{id}/edit',   [\App\Http\Controllers\Tenant\TicketController::class, 'edit'])   ->name('tickets.edit');
        Route::put('/tiket/{id}',        [\App\Http\Controllers\Tenant\TicketController::class, 'update']) ->name('tickets.update');
        Route::delete('/tiket/{id}',     [\App\Http\Controllers\Tenant\TicketController::class, 'destroy'])->name('tickets.destroy');

        Route::get('/perpanjangan',                              [RenewalController::class, 'index'])    ->name('renewal.index');
        Route::post('/perpanjangan/ajukan',                      [RenewalController::class, 'initiate'])->name('renewal.initiate');
        Route::get('/perpanjangan/konfirmasi/{bookingId}',       [RenewalController::class, 'show'])    ->name('renewal.confirm');
        Route::post('/perpanjangan/konfirmasi/{bookingId}',      [RenewalController::class, 'respond']) ->name('renewal.respond');
        Route::get('/perpanjangan/{renewalId}/detail',           [RenewalController::class, 'show'])       ->name('renewal.show');
        Route::get('/perpanjangan/{renewalId}/invoice',          [RenewalController::class, 'invoice'])    ->name('renewal.invoice');
        Route::get('/perpanjangan/{renewalId}/midtrans',         [RenewalController::class, 'payMidtrans'])->name('renewal.pay-midtrans');
        Route::post('/perpanjangan/{renewalId}/snap-token',      [RenewalController::class, 'snapToken'])  ->name('renewal.snap-token');
        Route::get('/perpanjangan/{id}/edit',                    [RenewalController::class, 'edit'])        ->name('renewal.edit');
        Route::put('/perpanjangan/{id}',                         [RenewalController::class, 'update'])      ->name('renewal.update');
        Route::delete('/perpanjangan/{id}',                      [RenewalController::class, 'destroy'])     ->name('renewal.destroy');
    });

// Calon Penghuni
Route::middleware(['auth', 'otp.verified', 'role:prospective_tenant'])
    ->prefix('calon-penghuni')
    ->name('prospective.')
    ->group(function () {
        Route::get('/dashboard', [HomeController::class, 'prospectiveDashboard'])->name('dashboard');
        Route::get('/bookings', [BookingController::class, 'myBookings'])->name('bookings');

        Route::prefix('waiting-list')->name('waiting-list.')->group(function () {
            Route::get('/',                      [ProspectiveWaitingListController::class, 'index'])              ->name('index');
            Route::get('/{id}',                  [ProspectiveWaitingListController::class, 'show'])               ->name('show');
            Route::get('/{id}/bayar-dp',         [ProspectiveWaitingListController::class, 'payDp'])             ->name('pay-dp');
            Route::post('/{id}/snap-token',      [ProspectiveWaitingListController::class, 'generateSnapToken']) ->name('snap-token');
            Route::post('/{id}/batalkan',        [ProspectiveWaitingListController::class, 'cancel'])            ->name('cancel');
            Route::post('/{id}/activate-tenant', [\App\Http\Controllers\Admin\WaitingListController::class, 'activateTenant'])->name('activate-tenant');
        });

        Route::prefix('tagihan')->name('invoices.')->group(function () {
            Route::get('/',                      [ProspectiveInvoiceController::class, 'index'])      ->name('index');
            Route::get('/{invoice}',             [ProspectiveInvoiceController::class, 'show'])       ->name('show');
            Route::get('/{invoice}/midtrans',    [ProspectiveInvoiceController::class, 'payMidtrans'])->name('pay-midtrans');
            Route::post('/{invoice}/snap-token', [ProspectiveInvoiceController::class, 'snapToken'])  ->name('snap-token');
            Route::post('/{invoice}/bayar',      [ProspectiveInvoiceController::class, 'pay'])        ->name('pay');
        });
    });

// Midtrans Webhook Untuk Pembayaran
Route::post('/midtrans/payment-callback', [\App\Http\Controllers\MidtransCallbackController::class, 'handle'])
    ->middleware(['auth', 'otp.verified'])
    ->name('midtrans.callback');
Route::post('/midtrans/webhook', [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);