<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Mobile\LoginRequest;
use App\Http\Resources\Api\TokenResource;

class LoginController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request['phone'])->first();
        if (!$user || !Hash::check($request['password'], $user->password))
        {
            return $this->apiResponse(false, 'wrong phone or password');
        }
        if ($user->pin_code != null) {
            return $this->apiResponse(true, "please activate your account to take access");
        }
        return $this->apiResponse(true, 'Success', new TokenResource($user)) ;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->apiResponse(1, 'Logged out Successfully');
    }
}
