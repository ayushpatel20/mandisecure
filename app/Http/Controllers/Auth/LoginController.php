<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->isAdmin()) {
                $adminId  = Auth::user()->id;
                $mobile   = Auth::user()->mobile;
                $remember = $request->boolean('remember');

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if (!$mobile) {
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Admin account is not configured for 2FA. Contact support.']);
                }

                try {
                    $code = $this->otpService->generate($mobile, 3);
                    $this->otpService->send($mobile, $code);
                } catch (\Throwable) {
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Failed to send admin OTP. Please try again.']);
                }

                session(['pending_admin_2fa' => [
                    'admin_id' => $adminId,
                    'remember' => $remember,
                ]]);

                return redirect()->route('admin.otp.show');
            }

            $request->session()->regenerate();
            return redirect(Auth::user()->dashboardRoute());
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
