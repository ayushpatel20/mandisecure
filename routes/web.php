<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController  as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController   as AdminCategory;
use App\Http\Controllers\Admin\ProductController    as AdminProduct;
use App\Http\Controllers\Admin\PaymentController    as AdminPayment;
use App\Http\Controllers\Admin\SettingController    as AdminSetting;
use App\Http\Controllers\Admin\OrderController      as AdminOrder;
use App\Http\Controllers\Admin\UserController       as AdminUser;
use App\Http\Controllers\Buyer\DashboardController  as BuyerDashboard;
use App\Http\Controllers\Buyer\ProductController    as BuyerProduct;
use App\Http\Controllers\Buyer\CategoryController   as BuyerCategory;
use App\Http\Controllers\Buyer\CartController       as BuyerCart;
use App\Http\Controllers\Buyer\CheckoutController   as BuyerCheckout;
use App\Http\Controllers\Buyer\OrderController      as BuyerOrder;
use App\Http\Controllers\Buyer\PaymentController    as BuyerPayment;
use App\Http\Controllers\Buyer\ProfileController    as BuyerProfile;
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\ProductController   as SellerProduct;
use App\Http\Controllers\Seller\PaymentController   as SellerPayment;
use App\Http\Controllers\Seller\OrderController     as SellerOrder;
use App\Http\Controllers\Seller\ProfileController   as SellerProfile;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\AdminOtpController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Serve uploaded files via PHP (Windows XAMPP junction workaround — /files/ has no public dir)
// realpath() check prevents path traversal (e.g. /files/../../../../.env)
Route::get('/files/{path}', function (string $path) {
    $base     = realpath(storage_path('app/public'));
    $fullPath = realpath($base . DIRECTORY_SEPARATOR . $path);
    if ($fullPath === false || !str_starts_with($fullPath, $base) || !file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

// Language switch (no auth, works from any page)
Route::get('/language/{locale}', function (string $locale) {
    $supported = ['en', 'hi', 'kn', 'ta'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
        cookie()->queue(cookie()->forever('app_locale', $locale));
    }
    return redirect()->back()->withHeaders([
        'Cache-Control' => 'no-store',
    ]);
})->where('locale', 'en|hi|kn|ta')->name('language.switch');

// Public website (no auth required)
Route::get('/',               [PublicController::class, 'home'])->name('public.home');
Route::get('/about',          [PublicController::class, 'about'])->name('public.about');
Route::get('/contact',        [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact',       [PublicController::class, 'contactSend'])->name('public.contact.send')->middleware('throttle:5,10');

// Legal pages
Route::get('/privacy-policy',   [PublicController::class, 'privacy'])->name('public.privacy');
Route::get('/terms-conditions', [PublicController::class, 'terms'])->name('public.terms');
Route::get('/refund-policy',    [PublicController::class, 'refund'])->name('public.refund');
Route::get('/shipping-policy',  [PublicController::class, 'shipping'])->name('public.shipping');

// SEO
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('public.sitemap');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');

    // Registration
    Route::get('/register',          [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register/buyer',   [RegisterController::class, 'registerBuyer'])->name('register.buyer')->middleware('throttle:5,1');
    Route::post('/register/seller',  [RegisterController::class, 'registerSeller'])->name('register.seller')->middleware('throttle:5,1');

    // OTP verification (registration step 2)
    Route::get('/register/otp/verify',  [OtpController::class, 'showVerify'])->name('otp.verify.show');
    Route::post('/register/otp/verify', [OtpController::class, 'verify'])->name('otp.verify')->middleware('throttle:10,1');
    Route::post('/register/otp/resend', [OtpController::class, 'resend'])->name('otp.resend')->middleware('throttle:3,5');

    // Admin 2FA OTP
    Route::get('/admin/2fa',         [AdminOtpController::class, 'showVerify'])->name('admin.otp.show');
    Route::post('/admin/2fa',        [AdminOtpController::class, 'verify'])->name('admin.otp.verify')->middleware('throttle:10,1');
    Route::post('/admin/2fa/resend', [AdminOtpController::class, 'resend'])->name('admin.otp.resend')->middleware('throttle:3,5');

    // Password reset
    Route::get('/forgot-password',         [ForgotPasswordController::class, 'showRequest'])->name('password.request');
    Route::post('/forgot-password',        [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [ResetPasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password',         [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email verification — requires auth, not guarded by verified itself
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect(Auth::user()->dashboardRoute());
        }
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($request->user()->dashboardRoute())->with('success', 'Email already verified.');
        }
        $request->fulfill();
        return redirect($request->user()->dashboardRoute())->with('success', 'Email verified successfully!');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect(Auth::user()->dashboardRoute());
        }
        Auth::user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent! Please check your inbox.');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Pending approval — only accessible to sellers awaiting or rejected approval
Route::get('/pending-approval', function () {
    $user = auth()->user();
    if (!$user->isSeller() || $user->isActive()) {
        return redirect($user->dashboardRoute());
    }
    return view('auth.pending-approval');
})->name('auth.pending')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Categories
        Route::resource('categories', AdminCategory::class)->except(['show']);

        // Products
        Route::resource('products', AdminProduct::class);
        Route::post('products/{product}/approve', [AdminProduct::class, 'approve'])->name('products.approve');
        Route::post('products/{product}/reject',  [AdminProduct::class, 'reject'])->name('products.reject');

        // Payments
        Route::get('payments',                     [AdminPayment::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}',           [AdminPayment::class, 'show'])->name('payments.show');
        Route::post('payments/{payment}/approve',  [AdminPayment::class, 'approve'])->name('payments.approve');
        Route::post('payments/{payment}/reject',   [AdminPayment::class, 'reject'])->name('payments.reject');
        Route::patch('payments/{payment}/status',  [AdminPayment::class, 'updateStatus'])->name('payments.update-status');

        // Settings
        Route::get('settings/payment',  [AdminSetting::class, 'paymentEdit'])->name('settings.payment');
        Route::post('settings/payment', [AdminSetting::class, 'paymentUpdate'])->name('settings.payment.update');

        // Orders
        Route::get('orders',                      [AdminOrder::class, 'index'])->name('orders.index');
        Route::get('orders/{order}',              [AdminOrder::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status',     [AdminOrder::class, 'updateStatus'])->name('orders.update-status');

        // Users
        Route::get('users',                       [AdminUser::class, 'index'])->name('users.index');
        Route::get('users/{user}',                [AdminUser::class, 'show'])->name('users.show');
        Route::post('users/{user}/approve',       [AdminUser::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject',        [AdminUser::class, 'reject'])->name('users.reject');
        Route::post('users/{user}/block',         [AdminUser::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock',       [AdminUser::class, 'unblock'])->name('users.unblock');
    });

// Buyer routes
Route::middleware(['auth', 'verified', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        Route::get('/dashboard', [BuyerDashboard::class, 'index'])->name('dashboard');

        // Marketplace
        Route::get('/products',          [BuyerProduct::class, 'index'])->name('products.index');
        Route::get('/products/{slug}',   [BuyerProduct::class, 'show'])->name('products.show');

        // Categories
        Route::get('/categories',        [BuyerCategory::class, 'index'])->name('categories.index');
        Route::get('/categories/{slug}', [BuyerCategory::class, 'show'])->name('categories.show');

        // Cart
        Route::get('/cart',                     [BuyerCart::class, 'index'])->name('cart.index');
        Route::post('/cart/add',                [BuyerCart::class, 'add'])->name('cart.add');
        Route::patch('/cart/items/{cartItem}',  [BuyerCart::class, 'update'])->name('cart.update');
        Route::delete('/cart/items/{cartItem}', [BuyerCart::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear',            [BuyerCart::class, 'clear'])->name('cart.clear');

        // Checkout
        Route::get('/checkout',  [BuyerCheckout::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [BuyerCheckout::class, 'place'])->name('checkout.place');

        // Orders
        Route::get('/orders',                   [BuyerOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',           [BuyerOrder::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/invoice',   [BuyerOrder::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{order}/tracking',  [BuyerOrder::class, 'tracking'])->name('orders.tracking');

        // Payments
        Route::get('/orders/{order}/payment',  [BuyerPayment::class, 'create'])->name('payment.create');
        Route::post('/orders/{order}/payment', [BuyerPayment::class, 'store'])->name('payment.store');
        Route::get('/payments',                [BuyerPayment::class, 'index'])->name('payments.index');

        // Profile
        Route::get('/profile',                 [BuyerProfile::class, 'edit'])->name('profile.edit');
        Route::post('/profile',                [BuyerProfile::class, 'update'])->name('profile.update');
        Route::post('/profile/password',       [BuyerProfile::class, 'changePassword'])->name('profile.password');
    });

// Seller routes
Route::middleware(['auth', 'verified', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/dashboard', [SellerDashboard::class, 'index'])->name('dashboard');
        Route::resource('products', SellerProduct::class);

        // Payments
        Route::get('/payments', [SellerPayment::class, 'index'])->name('payments.index');

        // Orders
        Route::get('/orders',                  [SellerOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',          [SellerOrder::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [SellerOrder::class, 'updateStatus'])->name('orders.update-status');

        // Profile
        Route::get('/profile',                 [SellerProfile::class, 'edit'])->name('profile.edit');
        Route::post('/profile',                [SellerProfile::class, 'update'])->name('profile.update');
        Route::post('/profile/password',       [SellerProfile::class, 'changePassword'])->name('profile.password');
    });
