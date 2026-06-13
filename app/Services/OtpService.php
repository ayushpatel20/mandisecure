<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Log;

class OtpService
{
    private const EXPIRY_MINUTES = 5;
    private const MAX_RESENDS    = 3;
    private const CODE_LENGTH    = 6;

    // Generate a new OTP for a mobile, invalidating any existing unused OTP.
    public function generate(string $mobile): string
    {
        OtpCode::where('mobile', $mobile)->where('used', false)->update(['used' => true]);

        $code = str_pad((string) random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        OtpCode::create([
            'mobile'       => $mobile,
            'code'         => hash('sha256', $code),
            'resend_count' => 0,
            'used'         => false,
            'expires_at'   => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        return $code;
    }

    // Send OTP via Twilio SMS. Falls back to log when Twilio is not configured (dev mode).
    public function send(string $mobile, string $code): void
    {
        $sid   = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from  = config('services.twilio.from');

        if (empty($sid) || empty($token) || empty($from)) {
            Log::info("MandiSecure OTP [{$mobile}]: {$code}");
            return;
        }

        $client = new \Twilio\Rest\Client($sid, $token);
        $client->messages->create('+91' . $mobile, [
            'from' => $from,
            'body' => "Your MandiSecure OTP is {$code}. Valid for 5 minutes. Do not share with anyone.",
        ]);
    }

    // Verify the entered code against the latest unused, unexpired OTP for the mobile.
    public function verify(string $mobile, string $inputCode): bool
    {
        $otp = OtpCode::where('mobile', $mobile)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp || $otp->isExpired()) {
            return false;
        }

        if (!hash_equals($otp->code, hash('sha256', $inputCode))) {
            $otp->increment('failed_attempts');
            if ($otp->failed_attempts >= 3) {
                $otp->update(['used' => true]);
            }
            return false;
        }

        $otp->update(['used' => true]);
        return true;
    }

    // Generate and return a new OTP for resend, carrying forward the resend count.
    // Returns null if max resend attempts have been reached.
    public function resend(string $mobile): ?string
    {
        $otp = OtpCode::where('mobile', $mobile)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp || $otp->resend_count >= self::MAX_RESENDS) {
            return null;
        }

        $newResendCount = $otp->resend_count + 1;
        $otp->update(['used' => true]);

        $code = str_pad((string) random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        OtpCode::create([
            'mobile'       => $mobile,
            'code'         => hash('sha256', $code),
            'resend_count' => $newResendCount,
            'used'         => false,
            'expires_at'   => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        return $code;
    }

    // Returns remaining resend attempts for a mobile (for display in the UI).
    public function remainingResends(string $mobile): int
    {
        $otp = OtpCode::where('mobile', $mobile)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp) {
            return 0;
        }

        return max(0, self::MAX_RESENDS - $otp->resend_count);
    }
}
