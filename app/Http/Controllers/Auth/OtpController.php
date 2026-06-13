<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    // Show OTP entry screen.
    public function showVerify()
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register')
                ->withErrors(['mobile' => 'Session expired. Please register again.']);
        }

        $mobile  = $pending['mobile'];
        $masked  = '+91 ' . str_repeat('X', 6) . substr($mobile, -4);
        $resends = $this->otpService->remainingResends($mobile);

        return view('auth.otp-verify', compact('masked', 'resends'));
    }

    // Verify the submitted OTP and create the user account.
    public function verify(Request $request)
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register')
                ->withErrors(['mobile' => 'Session expired. Please register again.']);
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ]);

        if (!$this->otpService->verify($pending['mobile'], $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired OTP. Please try again.']);
        }

        // VULN-3: Whitelist role — reject anything other than buyer/seller.
        $allowedRoles = ['buyer', 'seller'];
        if (!in_array($pending['type'], $allowedRoles, true)) {
            session()->forget('pending_registration');
            return redirect()->route('register')
                ->withErrors(['mobile' => 'Invalid registration type. Please register again.']);
        }
        $allowedRole = $pending['type'];

        // VULN-1: Password is already bcrypt-hashed in session.
        // Use DB::table() to bypass the User model's `hashed` cast (which would double-hash).
        $insert = [
            'name'       => $pending['name'],
            'mobile'     => $pending['mobile'],
            'email'      => $pending['email'],
            'password'   => $pending['password'],
            'role'       => $allowedRole,
            'status'     => $allowedRole === 'buyer' ? 'active' : 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($allowedRole === 'seller') {
            $insert['business_name'] = $pending['business_name'] ?? null;
            $insert['gst_number']    = $pending['gst_number']    ?? null;
        }

        $userId = DB::table('users')->insertGetId($insert);
        $user   = User::find($userId);

        session()->forget('pending_registration');

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable) {}

        Auth::login($user);
        $request->session()->regenerate();

        if ($allowedRole === 'seller') {
            return redirect()->route('auth.pending');
        }

        return redirect()->route('buyer.dashboard')
            ->with('success', 'Mobile verified! Welcome to MandiSecure.');
    }

    // Resend OTP (max 3 times).
    public function resend(Request $request)
    {
        $pending = session('pending_registration');

        if (!$pending) {
            return redirect()->route('register')
                ->withErrors(['mobile' => 'Session expired. Please register again.']);
        }

        $code = $this->otpService->resend($pending['mobile']);

        if ($code === null) {
            return back()->withErrors(['code' => 'Maximum resend attempts (3) reached. Please start registration again.']);
        }

        try {
            $this->otpService->send($pending['mobile'], $code);
        } catch (\Throwable) {
            return back()->withErrors(['code' => 'Failed to resend OTP. Please try again.']);
        }

        $resends = $this->otpService->remainingResends($pending['mobile']);

        return back()->with('otp_resent', "OTP resent successfully. {$resends} resend(s) remaining.");
    }
}
