<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseTrait;

    public function login(Request $request)
    {
        // Check the request if the valid user email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->responseError([], 'No user found.');
        }

        // Check the password
        if (Hash::check($request->password, $user->password)) {
            $tokenCreated = $user->createToken('authToken');

            $data = [
                'user'         => $user,
                'access_token' => $tokenCreated->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse($tokenCreated->token->expires_at)->toDateTimeString()
            ];

            return $this->responseSuccess($data, 'Logged in successfully.');
        }
    }
}
