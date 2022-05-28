<?php


namespace App\Traits;


use App\Models\Models\OtpVerify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ManagesOTP
{
    use SendSms;
    public function sendOTP($user)
    {
        $otp = mt_rand(10000,99999);
        $newOtp = OtpVerify::on('mysql::write')->create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(config('otp.validity'))
        ]);
        if ($newOtp) {
            $message = "Hello! Your TranSave Verification Code is $newOtp->otp. Code is valid for the next ".config('otp.validity')."minutes.";
            $this->sendSms($user->phone, $message);
            return $newOtp;
        }
        return null;
    }

    public function verifyOTP(array $data)
    {
        $verifyOtp = OtpVerify::on('mysql::read')->where([
            'otp' => $data['otp'],
            'user_id' => $data['user_id']
        ])->first();

        if (!empty($verifyOtp)) {
            if (Carbon::parse($verifyOtp->expires_at)->lt(Carbon::now())) {
                return [ 'status' => false, 'message' => 'expired' ];
            }
            return [ 'status' => true, 'message' => 'active' ];
        }
        return [ 'status' => false, 'message' => 'not found' ];
    }
}
