<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = array_merge($user->toArray(), [
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);

            return response()->json(['data' => $data]);
        } else {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
                'data' => null,
            ], 422);
        }
    }
}
