<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = User::create([
            'name'     => $data['name'],
            'mobile'   => $data['mobile'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => 'buyer',
            'status'   => 'active',
        ]);

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable) {}

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('buyer.dashboard')
            ->with('success', 'Welcome to MandiSecure! Your buyer account is ready.');
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

        $user = User::create([
            'name'          => $data['name'],
            'business_name' => $data['business_name'],
            'gst_number'    => $data['gst_number'] ?? null,
            'mobile'        => $data['mobile'],
            'email'         => $data['email'],
            'password'      => $data['password'],
            'role'          => 'seller',
            'status'        => 'pending',
        ]);

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable) {}

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('auth.pending');
    }
}
