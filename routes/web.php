<?php

use Illuminate\Support\Facades\Route;
use App\Services\BrevoEmailService;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyOTPController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCatalogController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\CsrfTokenController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test-brevo-api', function (BrevoEmailService $brevo) {
    $ok = $brevo->sendEmail(
        env('BREVO_TEST_RECIPIENT_EMAIL', 'example@example.com'),
        env('BREVO_TEST_RECIPIENT_NAME', 'Test User'),
        'Test Brevo API',
        '<h1>Halo!</h1><p>Ini test email dari Brevo HTTP API di Laravel.</p>'
    );

    return $ok ? 'Email terkirim (cek inbox/spam)' : 'Gagal kirim (cek log).';
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

// CSRF token refresh endpoint
Route::get('/csrf-token', [CsrfTokenController::class, 'refresh'])->name('csrf.refresh');

// Blog routes
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// About route
Route::get('/about', function () {
    return view('about');
})->name('about');

// Registration and OTP routes for guests
Route::middleware('guest')->group(function () {
    // Registration routes
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    // OTP verification routes
    Route::get('verify-otp', [VerifyOTPController::class, 'create'])
        ->name('verify.otp');
    Route::post('verify-otp', [VerifyOTPController::class, 'store'])
        ->name('verify.otp.store');

    // Resend OTP route
    Route::post('resend-otp', [VerifyOTPController::class, 'resend'])
        ->name('otp.resend');

    // Google OAuth routes
    Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('auth.google');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});

// Laravel default dashboard removed to avoid conflict with partner dashboard
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

// Public event routes
Route::get('catalog', [EventCatalogController::class, 'index'])->name('catalog.index');
// Unified public detail route by slug (works for admin and partner events)
Route::get('events/{slug}', [App\Http\Controllers\PublicEventUnifiedController::class, 'show'])
    ->name('public.events.show');
// Existing id-based route kept for backward compatibility (admin-created events)
Route::get('events/id/{event}', [EventController::class, 'show'])->name('events.show');

// Legacy PartnerEvent detail (redirect to unified slug route)
Route::get('partner-events/{event}', function (App\Models\PartnerEvent $event) {
    return redirect()->route('public.events.show', $event->slug);
})->name('public.partner-events.show');

// Event ticket routes (authentication required)
Route::middleware(['auth'])->group(function () {
    Route::get('events/{event}/tickets', [EventController::class, 'tickets'])->name('events.tickets');
    Route::get('events/{event}/payment', [EventController::class, 'payment'])->name('events.payment');
});

// Event registration and attendance routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::post('events/{event}/register', [EventRegistrationController::class, 'store'])
        ->name('events.register');
    Route::get('events/{event}/success/{participant}', [EventRegistrationController::class, 'success'])
        ->name('events.registration.success');
    Route::get('events/{event}/attendance', [AttendanceController::class, 'show'])
        ->name('attendance.show');
    Route::post('events/{event}/attendance', [AttendanceController::class, 'store'])
        ->name('attendance.store');
    
    // My Events (Registered Events)
    Route::get('my-events', [App\Http\Controllers\MyEventsController::class, 'index'])
        ->name('my-events.index');
    
    // Wishlist
    Route::get('wishlist', [App\Http\Controllers\WishlistController::class, 'index'])
        ->name('wishlist.index');
    
    // User-Generated Events (Create & Manage Own Events)
    Route::prefix('user/events')->name('user.events.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserEventController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserEventController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserEventController::class, 'store'])->name('store');
        Route::get('/{event}', [App\Http\Controllers\UserEventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [App\Http\Controllers\UserEventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [App\Http\Controllers\UserEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [App\Http\Controllers\UserEventController::class, 'destroy'])->name('destroy');
    });
    
    // Event Reviews
    Route::post('events/{event}/review', [App\Http\Controllers\ReviewController::class, 'store'])
        ->name('events.review.store');
    Route::put('events/{event}/review/{review}', [App\Http\Controllers\ReviewController::class, 'update'])
        ->name('events.review.update');
    Route::delete('events/{event}/review/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])
        ->name('events.review.destroy');

    // Partner Event Reviews (legacy) -> redirect to unified slug page
    Route::match(['post','put','delete'],'partner-events/{event}/review/{review?}', function (App\Models\PartnerEvent $event) {
        return redirect()->route('public.events.show', $event->slug);
    })->name('public.partner-events.review.redirect');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export', [App\Http\Controllers\Admin\DashboardController::class, 'exportData'])->name('dashboard.export');
    Route::resource('events', App\Http\Controllers\Admin\EventController::class);
    Route::resource('participants', App\Http\Controllers\Admin\ParticipantController::class);
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::post('/users/{user}/toggle-admin', [App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    
    // Admin Attendance Management
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/checkin', [App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    
    // Certificate Management
    Route::post('/events/{event}/generate-certificates', [App\Http\Controllers\CertificateController::class, 'generateEventCertificates'])->name('events.generate-certificates');
    
    // Event Approval Management
    Route::get('/events/pending', [App\Http\Controllers\Admin\EventApprovalController::class, 'index'])->name('events.pending');
    Route::post('/events/{event}/approve', [App\Http\Controllers\Admin\EventApprovalController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject', [App\Http\Controllers\Admin\EventApprovalController::class, 'reject'])->name('events.reject');
});

// API route for mobile/kiosk (optional)
Route::post('/api/attendance/checkin', [App\Http\Controllers\Admin\AttendanceController::class, 'checkIn']);
// Session-based attendance check-in
Route::post('/api/attendance/session/checkin', [App\Http\Controllers\AttendanceController::class, 'sessionCheckIn']);

// Certificate routes (Legacy)
Route::get('/certificate/search', [App\Http\Controllers\CertificateController::class, 'searchPage'])
    ->name('certificate.search.page');
Route::get('/certificate/find', [App\Http\Controllers\CertificateController::class, 'search'])
    ->name('certificate.search');
Route::get('/certificate/{participant}/download', [App\Http\Controllers\CertificateController::class, 'generate'])
    ->name('certificate.generate');
Route::get('/certificate/{participant}/view', [App\Http\Controllers\CertificateController::class, 'view'])
    ->name('certificate.view');

// New Certificate System Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'userCertificates'])
        ->name('certificates.index');
    Route::get('/certificates/{certificate}/download', [App\Http\Controllers\CertificateController::class, 'download'])
        ->name('certificates.download');
});

// Public certificate verification
Route::get('/certificates/verify', [App\Http\Controllers\CertificateController::class, 'searchByCertificateNumber'])
    ->name('certificates.verify');

// Attendance verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/attendance', [App\Http\Controllers\AttendanceController::class, 'verificationPage'])
        ->name('events.attendance.verification');
    Route::post('/attendance/verify', [App\Http\Controllers\AttendanceController::class, 'verifyToken'])
        ->name('attendance.verify');
});

// E-Ticket routes
Route::middleware(['auth'])->group(function () {
    Route::get('/ticket/{participant}/download', [App\Http\Controllers\TicketController::class, 'download'])
        ->name('ticket.download');
    Route::get('/ticket/{participant}/view', [App\Http\Controllers\TicketController::class, 'view'])
        ->name('ticket.view');
});

// Session extension route for timeout handling
Route::post('/extend-session', [App\Http\Controllers\SessionController::class, 'extend'])
    ->middleware('auth')
    ->name('session.extend');

// Payment routes
Route::post('/payment/create', [App\Http\Controllers\PaymentController::class, 'createPayment'])
    ->name('payment.create');
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'handleNotification'])
    ->name('payment.notification');
Route::get('/payment/finish', [App\Http\Controllers\PaymentController::class, 'finish'])
    ->name('payment.finish');
Route::get('/payment/unfinish', [App\Http\Controllers\PaymentController::class, 'unfinish'])
    ->name('payment.unfinish');
Route::get('/payment/error', [App\Http\Controllers\PaymentController::class, 'error'])
    ->name('payment.error');

// Admin Routes - Manage Everything
Route::prefix('admin')->middleware(['auth', App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Event Management
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'show'])->name('events.show');
    Route::put('/events/{event}/approve', [App\Http\Controllers\Admin\EventController::class, 'approve'])->name('events.approve');
    Route::put('/events/{event}/reject', [App\Http\Controllers\Admin\EventController::class, 'reject'])->name('events.reject');
    
    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/activate', [App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
    Route::put('/users/{user}/deactivate', [App\Http\Controllers\Admin\UserController::class, 'deactivate'])->name('users.deactivate');
    
    // Partner Management
    Route::get('/partners', [App\Http\Controllers\Admin\PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/{partner}', [App\Http\Controllers\Admin\PartnerController::class, 'show'])->name('partners.show');
    Route::put('/partners/{partner}/verify', [App\Http\Controllers\Admin\PartnerController::class, 'verify'])->name('partners.verify');
    Route::put('/partners/{partner}/reject', [App\Http\Controllers\Admin\PartnerController::class, 'reject'])->name('partners.reject');
    
    // Partner Events Management
    Route::get('/partner-events', [App\Http\Controllers\Admin\PartnerEventController::class, 'index'])->name('partner-events.index');
    Route::get('/partner-events/{event}', [App\Http\Controllers\Admin\PartnerEventController::class, 'show'])->name('partner-events.show');
    Route::put('/partner-events/{event}/approve', [App\Http\Controllers\Admin\PartnerEventController::class, 'approve'])->name('partner-events.approve');
    Route::put('/partner-events/{event}/reject', [App\Http\Controllers\Admin\PartnerEventController::class, 'reject'])->name('partner-events.reject');
    Route::put('/partner-events/{event}/withdraw', [App\Http\Controllers\Admin\PartnerEventController::class, 'withdraw'])->name('partner-events.withdraw');
});

// Diantara Nexus B2B Portal
Route::prefix('diantaranexus')->name('diantaranexus.')->group(function () {
    // Partner Authentication Routes (Guest only)
    Route::middleware('guest:partner')->group(function () {
        Route::get('/login', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'showLoginForm'])
            ->name('login');
        Route::post('/login', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'login'])
            ->name('login.submit');
        
        Route::get('/register', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'showRegistrationForm'])
            ->name('register');
        Route::post('/register', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'register'])
            ->name('register.submit');
        
        // OTP Verification Routes
        Route::get('/verify-otp', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'showVerifyOtp'])
            ->name('verify-otp');
        Route::post('/verify-otp', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'verifyOtp'])
            ->name('verify-otp.submit');
        Route::post('/resend-otp', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'resendOtp'])
            ->name('resend-otp');

        // Partner password reset routes
        Route::get('/forgot-password', [App\Http\Controllers\Partner\Auth\PartnerPasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [App\Http\Controllers\Partner\Auth\PartnerPasswordResetLinkController::class, 'store'])
            ->name('password.email');
        Route::get('/reset-password/{token}', [App\Http\Controllers\Partner\Auth\PartnerNewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('/reset-password', [App\Http\Controllers\Partner\Auth\PartnerNewPasswordController::class, 'store'])
            ->name('password.store');
        
        // Organization Setup Routes
        Route::get('/setup-organization', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'showOrganizationSetup'])
            ->name('setup-organization');
        Route::post('/setup-organization', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'setupOrganization'])
            ->name('setup-organization.submit');
    });

    // Partner Authenticated Routes
    Route::middleware('auth:partner')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Partner\Auth\PartnerAuthController::class, 'logout'])
            ->name('logout');
        
        Route::get('/dashboard', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'index'])
            ->name('dashboard');
        Route::post('/select-organization/{organizationId}', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'selectOrganization'])
            ->name('select-organization');
        
        Route::get('/profile', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [App\Http\Controllers\Partner\PartnerDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        // Organization Info
        Route::get('/organization', [App\Http\Controllers\Partner\PartnerOrganizationController::class, 'show'])
            ->name('organization.show');
        Route::put('/organization', [App\Http\Controllers\Partner\PartnerOrganizationController::class, 'update'])
            ->name('organization.update');

        // Event Management Routes
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [App\Http\Controllers\Partner\PartnerEventController::class, 'index'])
                ->name('index');
            Route::get('/create', [App\Http\Controllers\Partner\PartnerEventController::class, 'create'])
                ->name('create');
            Route::post('/create/step1', [App\Http\Controllers\Partner\PartnerEventController::class, 'storeStep1'])
                ->name('create.step1.store');
            Route::get('/create/{event}/step2', [App\Http\Controllers\Partner\PartnerEventController::class, 'createStep2'])
                ->name('create.step2');
            Route::post('/create/{event}/step2', [App\Http\Controllers\Partner\PartnerEventController::class, 'storeStep2'])
                ->name('create.step2.store');
            Route::get('/create/{event}/step3', [App\Http\Controllers\Partner\PartnerEventController::class, 'createStep3'])
                ->name('create.step3');
            Route::post('/create/{event}/step3', [App\Http\Controllers\Partner\PartnerEventController::class, 'storeStep3'])
                ->name('create.step3.store');
            Route::get('/{event}', [App\Http\Controllers\Partner\PartnerEventController::class, 'show'])
                ->name('show');
            Route::get('/{event}/certificate/preview', [App\Http\Controllers\Partner\PartnerEventController::class, 'previewCertificate'])
                ->name('certificate.preview');
            Route::post('/{event}/submit-review', [App\Http\Controllers\Partner\PartnerEventController::class, 'submitForReview'])
                ->name('submit-review');
            Route::get('/{event}/edit', [App\Http\Controllers\Partner\PartnerEventController::class, 'edit'])
                ->name('edit');
            Route::delete('/{event}', [App\Http\Controllers\Partner\PartnerEventController::class, 'destroy'])
                ->name('destroy');
        });
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
