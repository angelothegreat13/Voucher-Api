<?php

namespace App\Http\Controllers\Api;

use App\User;

use GuzzleHttp\Client;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register()
    {
        $validatedData = request()->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt(request()->password);

        $user = User::create($validatedData);

        $http = new Client;

        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => '89EnHnUnzBmy7t2hI1qCiTCDIDqUT5GNg4lm12UK',
                'username' => request()->email,
                'password' => request()->password,
                'scope' => ''
            ]
        ]);
    
        return response(['data' => json_decode((string) $response->getBody(), true)]);
    }

    public function login()
    {   
        $loginData = request()->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in',
            'token' => $accessToken
        ]);
    }


    public function refreshToken() 
    {
		$http = new Client;

		$response = $http->post(url('oauth/token'), [
		    'form_params' => [
		        'grant_type' => 'refresh_token',
		        'refresh_token' => request('refresh_token'),
		        'client_id' => '2',
		        'client_secret' => '2BS3lMjC5vbA4NFOtHC48Up0AUXRTuPV7My84BGR',
		        'scope' => '',
		    ],
		]);

		return json_decode((string) $response->getBody(), true);
	}

}