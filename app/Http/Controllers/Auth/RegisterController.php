<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function registerBuyer(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'mobile'   => ['required', 'string', 'max:20', 'unique:users,mobile', 'regex:/^[6-9]\d{9}$/'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $otp = new OtpService();
        $code = $otp->generate($data['mobile']);

        try {
            $otp->send($data['mobile'], $code);
        } catch (\Throwable) {
            return back()->withInput()->withErrors([
                'mobile' => 'Failed to send OTP. Please check your mobile number and try again.',
            ]);
        }

        session(['pending_registration' => [
            'type'     => 'buyer',
            'name'     => $data['name'],
            'mobile'   => $data['mobile'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]]);

        return redirect()->route('otp.verify.show');
    }

    public function registerSeller(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'business_name' => ['required', 'string', 'max:150'],
            'gst_number'    => ['nullable', 'string', 'max:20'],
            'mobile'        => ['required', 'string', 'max:20', 'unique:users,mobile', 'regex:/^[6-9]\d{9}$/'],
            'email'         => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $otp = new OtpService();
        $code = $otp->generate($data['mobile']);

        try {
            $otp->send($data['mobile'], $code);
        } catch (\Throwable) {
            return back()->withInput()->withErrors([
                'mobile' => 'Failed to send OTP. Please check your mobile number and try again.',
            ]);
        }

        session(['pending_registration' => [
            'type'          => 'seller',
            'name'          => $data['name'],
            'business_name' => $data['business_name'],
            'gst_number'    => $data['gst_number'] ?? null,
            'mobile'        => $data['mobile'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
        ]]);

        return redirect()->route('otp.verify.show');
    }
}
