<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private static array $paymentKeys = [
        'payment_upi_id',
        'payment_bank_account_holder',
        'payment_bank_account_number',
        'payment_bank_ifsc',
        'payment_bank_name',
    ];

    public function paymentEdit()
    {
        $settings = [];
        foreach (self::$paymentKeys as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        return view('admin.settings.payment', compact('settings'));
    }

    public function paymentUpdate(Request $request)
    {
        $request->validate([
            'payment_upi_id'               => 'nullable|string|max:100',
            'payment_bank_account_holder'  => 'nullable|string|max:120',
            'payment_bank_account_number'  => 'nullable|string|max:30',
            'payment_bank_ifsc'            => 'nullable|string|max:20',
            'payment_bank_name'            => 'nullable|string|max:80',
        ]);

        foreach (self::$paymentKeys as $key) {
            Setting::set($key, $request->input($key));
        }

        return back()->with('success', 'Payment settings saved successfully.');
    }
}
