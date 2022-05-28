<?php

namespace App\Http\Controllers;

use App\Classes\BankRegistrationHook;
use App\Classes\PeaceAPI;
use App\Models\Commission;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->vfd = new BankRegistrationHook();
        $this->peace = new PeaceAPI;
    }

    public function transfer(Request $request)
    {
//        return $this->peace->nameEnquiry($request);
        $settings = Settings::on('mysql::read')->where('control_type')->first();

        switch ($settings) {
            case 'vfd':
                $this->vfd->bankTransfer($request);
                break;
            case 'pfmb':
                $this->peace->nameEnquiry($request);
                break;
        }
    }

    public function bankCodes()
    {

    }

    public function setCommission(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'percent' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()],422);
        }

        $type = $request->input('type');
        Commission::updateOrCreate([
            'type' => $type
        ],$request->all());

        return response()->json(['message' => 'Commission successfully set'],201);
    }
}
