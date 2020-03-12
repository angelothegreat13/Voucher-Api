<?php

namespace App\Http\Controllers\Api;

use DB;

use GuzzleHttp\Client;
use App\PuregoldVoucher;

use App\PuregoldVoucherTransaction;

use App\Http\Controllers\Controller;

class PureGoldApiController extends Controller
{
    /**
     *  User authorization process
     * 
     *  @param string $email  
     *  @param string password
     * 
     *  @return array success && access token
    **/
    protected static function userAuth($email,$password)
    {
        $loginData = request()->validate([
            'email' => 'email|required',
            'password' => 'required|min:6|max:50'
        ]);

        if (!auth()->attempt($loginData)) {
            return ['success' => false, 'message' => 'Invalid Credentials'];
        }

        return [
            'success' => true,
            'access_token' => auth()->user()->createToken('puregold-access-token')->accessToken
        ];
    }

    /**
     *  Response a message to user
     * 
     *  @param string $msg 
     *  @param string $success
     * 
     *  @return json array message
    **/
    protected static function message($msg, $success = false) 
    {
        return response()->json(['success' => $success, 'message' => $msg]);
    }

    /**
     *  Get voucher data
     * 
     *  @param string $accessToken
     *  @param string $code
     * 
     *  @return object $response
    **/
    protected static function getVoucherData($accessToken,$code) 
    {
        $response = (new Client)->request('GET', route('puregold-vouchers.show', $code), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$accessToken,
            ],
        ]);

        return json_decode((string) $response->getBody());
    }

    public function gateway()
    {
        $loginData = self::userAuth(request()->email,request()->password);

        DB::raw('LOCK TABLE users WRITE');

        if (!$loginData['success']) {
            return $loginData;
        }
            
        request()->validate([
            'code' => 'required',
            'amount' => 'required|numeric'
        ]);
 
        $voucherData = self::getVoucherData($loginData['access_token'],request()->code); 

        $responseArr = ['success' => false, 'message' => ''];

        if ($voucherData === NULL) {
            return self::message('Voucher code does not exist');
        }

        $balance = $voucherData->balance;

        if ($voucherData->is_used === 1) {
            return self::message('Voucher code already used');
        }

        if (request()->amount > $balance) {
            return self::message('Amount is greater than voucher amount');
        }

        if (date('Y-m-d h:i:s') >= $voucherData->expiration_date) {
            return self::message('Voucher code expired already');
        }
    
        PuregoldVoucher::find($voucherData->id)
            ->update([
                'is_used' => 0,
                'balance' => $balance - request()->amount
            ]);

        DB::raw('LOCK TABLE puregold_vouchers WRITE');

        PuregoldVoucherTransaction::create([
            'puregold_voucher_id' => $voucherData->id,
            'balance_before_trans' => $balance,
            'amount_deducted' => request()->amount,
            'balance_after_trans' => $balance - request()->amount
        ]);

        DB::raw('LOCK TABLE puregold_voucher_transactions WRITE');

        DB::RAW('UNLOCK TABLES');
        
        return self::message('Transaction Successful',true);
    }
}
