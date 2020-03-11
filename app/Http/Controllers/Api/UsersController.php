<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function detail()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

}
