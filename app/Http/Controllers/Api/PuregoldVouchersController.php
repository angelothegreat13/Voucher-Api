<?php

namespace App\Http\Controllers\Api;

use DB;

use Validator;

use App\PuregoldVoucher;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;

class PuregoldVouchersController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => false,
            'vouchers' => PuregoldVoucher::all()            
        ]);
    }

    /**
     *  Decrypt and get all the voucher codes in the table 
     *  
     *  @return array $decryptedCodes
    **/
    protected static function decryptVoucherCodes()
    {
        $encryptedCodes = PuregoldVoucher::select('code')->get();
        $decryptedCodes = [];
    
        foreach ($encryptedCodes as $encryptedCode) {
            $decryptedCodes[] = Crypt::decrypt($encryptedCode->code);
        }

        return $decryptedCodes;
    }

    public function store()
    {
        DB::raw('lock tables puregold_vouchers write');

        $v = Validator::make(request()->all(), [
            'code' => 'required|size:16',
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required|integer'
        ]);

        $voucherCodes = self::decryptVoucherCodes();
    
        $v->after(function ($v) use ($voucherCodes) {
            if (in_array(request()->code,$voucherCodes)) {
                $v->errors()->add('code', 'Voucher code already exists');
            }
        });

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()]);
        }

        $validatedData = $v->valid();

        $validatedData['code'] = Crypt::encrypt(request()->code);
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['balance'] = request()->amount;
        $validatedData['expiration_date'] = date('Y-m-d h:i:s', strtotime('+1 year'));
        
        PuregoldVoucher::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Puregold Voucher successfully added'
        ]);
    }

    /**
     *  Descrypt and get the data of voucher code
     * 
     *  @param string $code 
     *  
     *  @return array|NULL $codeData
    **/
    protected static function getVoucherCodeData($code)
    {
        DB::raw('lock tables puregold_vouchers read');

        $vouchers = PuregoldVoucher::all();
        $codeData = NULL;
    
        foreach ($vouchers as $voucher) {
            if ($code == Crypt::decrypt($voucher->code)) {
                $codeData = $voucher;
                break;
            }
        }

        return $codeData;
    }

    public function show($code)
    {
        return self::getVoucherCodeData($code);
    }

}