<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait ManagesBVN
{
    public function verifyBVN(array $data)
    {
        $bvn = $data['bvn'];
        $dob = $data['dob'];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('vfd.key')
        ])->post(config('vfd.url').'client/create?bvn='.$bvn.'&dateOfBirth='.$dob.'&wallet-credentials='.config('vfd.wallet_id'),[
            'payload' => 'string',
        ]);

        if ($response->json()['data']) {
            return $response->json()['data'];
        }
        return null;
    }

}
