<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function showVerify()
    {
        $pending = session('pending_admin_2fa');

        if (!$pending) {
            return redirect()->route('login');
        }

        $admin = User::find($pending['admin_id']);

        if (!$admin || !$admin->isAdmin()) {
            session()->forget('pending_admin_2fa');
            return redirect()->route('login');
        }

        $mobile  = $admin->mobile;
        $masked  = '+91 ' . str_repeat('X', 6) . substr($mobile, -4);
        $resends = $this->otpService->remainingResends($mobile);

        return view('auth.admin-otp-verify', compact('masked', 'resends'));
    }

    public function verify(Request $request)
    {
        $pending = session('pending_admin_2fa');

        if (!$pending) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ]);

        $admin = User::find($pending['admin_id']);

        if (!$admin || !$admin->isAdmin()) {
            session()->forget('pending_admin_2fa');
            return redirect()->route('login')
                ->withErrors(['email' => 'Invalid session. Please login again.']);
        }

        if (!$this->otpService->verify($admin->mobile, $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired OTP. Please try again.']);
        }

        $remember = $pending['remember'];
        session()->forget('pending_admin_2fa');

        Auth::loginUsingId($admin->id, $remember);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function resend(Request $request)
    {
        $pending = session('pending_admin_2fa');

        if (!$pending) {
            return redirect()->route('login');
        }

        $admin = User::find($pending['admin_id']);

        if (!$admin || !$admin->isAdmin()) {
            session()->forget('pending_admin_2fa');
            return redirect()->route('login');
        }

        $code = $this->otpService->resend($admin->mobile, 3);

        if ($code === null) {
            return back()->withErrors(['code' => 'Maximum resend attempts (3) reached. Please login again.']);
        }

        try {
            $this->otpService->send($admin->mobile, $code);
        } catch (\Throwable) {
            return back()->withErrors(['code' => 'Failed to resend OTP. Please try again.']);
        }

        $resends = $this->otpService->remainingResends($admin->mobile);

        return back()->with('otp_resent', "OTP resent successfully. {$resends} resend(s) remaining.");
    }
}
