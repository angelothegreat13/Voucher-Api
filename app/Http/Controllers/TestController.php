<?php

namespace App\Http\Controllers;

use App\PuregoldVoucher;

use Illuminate\Support\Facades\Crypt;

class TestController extends Controller
{   
    public function index()
    {
        $code = 'fuken178Jhhal3ef';

        $vouchers = PuregoldVoucher::all();
        $voucherCodes = NULL;
    
        foreach ($vouchers as $voucher) {
            if ($code == Crypt::decrypt($voucher->code)) {
                $voucherCodes = $voucher;
                break;
            }
        }

        return view('api.test',compact('voucherCodes'));
    }

}
