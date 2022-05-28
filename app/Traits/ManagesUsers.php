<?php


namespace App\Traits;


use App\Enums\AccountRequestType;
use App\Enums\AccountTypes;
use App\Enums\WithdrawalLimit;
use App\Mail\PremiumAccountEmail;
use App\Models\AccountRequest;
use App\Models\CustomerValidation;
use App\Models\Kyc;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

trait ManagesUsers
{

    private function ordinaryUser($user_id)
    {
        $exists = Kyc::on('mysql::read')->where('user_id', $user_id)->exists();
        if (!$exists) {
            $user = User::on('mysql::read')->find($user_id);
            $fullname = $user->name;
            $name_array = explode(' ', $fullname);

            $kyc = Kyc::on('mysql::write')->create([
                'user_id' => $user_id,
                'first_name' => $name_array[0] ?? '',
                'last_name' =>  $name_array[1] ?? ''
            ]);
        }

       $user = User::on('mysql::read')->whereId($user_id)->whereNotNull('sex')
           ->whereNotNull('name')->whereNotNull('bvn')
           ->whereNotNull('email')->whereNotNull('phone')->first();
       $verified = CustomerValidation::on('mysql::read')->where('user_id', $user_id)->first();
       if ($user) {
           if ($verified->authorized_stat == 1) {
               if ($user->account_type_id !== AccountTypes::ORDINARY_USER) {
                   $user->update([
                       'account_type_id' => AccountTypes::ORDINARY_USER,
                       'withdrawal_limit' => WithdrawalLimit::ORDINARY_ACCOUNT,
                   ]);
               }
           }else {
               $user->update([
                   'account_type_id' => AccountTypes::UNVERIFIED_USER,
                   'withdrawal_limit' => WithdrawalLimit::UNVERIFIED_ACCOUNT,
               ]);
           }

           return $user;
       }
       return null;
    }

    private function classicUser($user_id)
    {
        if ($this->ordinaryUser($user_id)) {
            $kyc = Kyc::on('mysql::read')->where('user_id', $user_id)->whereNotNull('proof_of_address_url')->whereNotNull('id_card_url')
                ->whereNotNull('last_name')->whereNotNull('first_name')->whereNotNull('id_card_number')->whereNotNull('address')
                ->whereNotNull('mother_maiden_name')->whereNotNull('next_of_kin')->whereNotNull('next_of_kin_contact')->whereNotNull('id_card_type_id')
                ->whereNotNull('city')->whereNotNull('state_id')->whereNotNull('lga_id')->whereNotNull('passport_url')->first();
            if ($kyc) {
                $user = $kyc->user;
                if ($user->account_type_id !== AccountTypes::CLASSIC_USER) {
                    $user->update([
                        'account_type_id' => AccountTypes::CLASSIC_USER,
                        'withdrawal_limit' => WithdrawalLimit::CLASSIC_ACCOUNT,
                    ]);
                }else {
                    $user->update([
                        'account_type_id' => AccountTypes::ORDINARY_USER,
                        'withdrawal_limit' => WithdrawalLimit::ORDINARY_ACCOUNT,
                    ]);
                }
                return $user;
            }
        }
        return null;
    }

    public function updateUserAccountType($user_id)
    {
        if ($this->classicUser($user_id)) {
            $kyc = Kyc::on('mysql::read')->where('user_id', $user_id)->whereNotNull('guarantor')->whereNotNull('guarantor_contact')
                ->whereNotNull('country_of_residence_id')->whereNotNull('country_of_origin_id')->first();
            if ($kyc) {
                $user = $kyc->user;
                $request = AccountRequest::where([
                    'user_id' => $user->id,
                    'request_type' => AccountRequestType::UPGRADE
                ])->exists();

                if (!$request) {
                    AccountRequest::create([
                        'user_id' => $user->id,
                        'account_type_id' => AccountTypes::CLASSIC_USER,
                        'request_type' => AccountRequestType::UPGRADE,
                        'status' => 0,
                        'content' => '<div>Please can you activate my pending request for a premium account</div>'
                    ]);
                    Mail::to('support@transave.com.ng')->send(new PremiumAccountEmail($user));
                    return $user;
                }

            }
        }
        return null;
    }

    public function ownsRecord($id)
    {
        return auth()->id() === $id;
    }

    public function saveUserActivity($activity, $type, $user_id)
    {
        $ip = request()->ip();
        if ($ip == '127.0.0.1') {
            $ip = '';
        }
        $data = Location::get($ip);
        return UserActivity::on('mysql::write')->create([
            'user_id' => $user_id,
            'activity' => $activity,
            'type' => $type,
            'city' => $data->cityName,
            'region' => $data->regionName,
            'country' => $data->countryName,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
        ]);
    }


    public function rejectPremium ($user_id)
    {
        $kyc = Kyc::on('mysql::read')->where('user_id', $user_id)->orWhere('id', $user_id)->first();
        $updated = $kyc->update([
            'guarantor' => '',
            'guarantor_contact' => '',
            'country_of_residence_id' => 161,
            'country_of_origin_id' => 161,
        ]);

        if ($updated) {
            $user = $kyc->user;
            $user->update([
                'account_type_id' => AccountTypes::CLASSIC_USER,
                'withdrawal_limit' => WithdrawalLimit::CLASSIC_ACCOUNT,
            ]);
            return Kyc::find($kyc->id);
        }
        return null;
    }

    public function upgradeUserAccount($user_id) {
        $account = AccountRequest::on('mysql::read')->where('user_id', $user_id)->orWhere('id', $user_id)->first();
        $user = User::find($account->user_id);
        $user->update([
            'account_type_id' => $user->account_type_id < 4 ? $user->account_type_id + 1 : $user->account_type_id,
        ]);
        $updated = User::find($user->id);
        $withdrawalLimit = WithdrawalLimit::UNVERIFIED_ACCOUNT;
        switch ($updated->account_type_id) {
            case 1: {
                break;
            }
            case 2: {
                $withdrawalLimit = WithdrawalLimit::ORDINARY_ACCOUNT;
                break;
            }
            case 3: {
                $withdrawalLimit = WithdrawalLimit::CLASSIC_ACCOUNT;
                break;
            }
            case 4: {
                $withdrawalLimit = WithdrawalLimit::PREMIUM_ACCOUNT;
                break;
            }
            default: {
                $withdrawalLimit = WithdrawalLimit::UNVERIFIED_ACCOUNT;
                break;
            }
        }
        return $updated->update([
            'withdrawal_limit' => $withdrawalLimit,
        ]);

    }

    public function isAdminShutdownStatus()
    {
        if (auth()->check()) {
            if(auth()->user()->shutdown_level === 2) {
                auth()->logout();
                return true;
            }else {
                return false;
            }
        }
        return false;
    }
}
