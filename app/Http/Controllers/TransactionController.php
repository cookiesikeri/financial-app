<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Enums\TransactionType;
use App\Jobs\TransferJob;
use App\Mail\CreditEmail;
use App\Models\Settings;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ManagesTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Classes\PeaceAPI;
use App\Classes\BankRegistrationHook;

class TransactionController extends Controller
{
    use ManagesTransactions;

    private $peaceTransfer;
    private $vfdTransfer;

    public function __construct()
    {
        $this->peaceTransfer = new PeaceAPI;
        $this->vfdTransfer = new BankRegistrationHook;
    }

    public function outwardTransfer(Request $request)
    {
        $settings = Settings::on('mysql::read')->where('control_type','transfer')->first();
        if(!empty($settings)) {
            switch ($settings->name) {
                case 'VFD':
                    return $this->vfdTransfer->bankTransfer($request);
                case 'APPZONE':
                    return $this->peaceTransfer->interTransfer($request);
            }
        } else {
            return response()->json(['message' => 'Transacction not configure for this app'],403);
        }

    }

    public function bankCodeController()
    {
        $settings = Settings::on('mysql::read')->where('control_type','transfer')->first();
        if(!empty($settings)) {
            switch ($settings->name) {
                case 'VFD':
                    //Switch Bank Code
//                    return $this->vfdTransfer->bankTransfer($request);
                case 'APPZONE':
                    //Use for Transave
                    
//                    return $this->peaceTransfer->interTransfer($request);
            }
        } else {
            return response()->json(['message' => 'Transacction not configure for this app'],403);
        }
    }
}
