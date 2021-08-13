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
            $user->generateToken();

            return response()->json([
                'data' => $user->toArray(),
            ]);
        } else {
            return response()->json([
                'message' => 'The provided credentials do not match our records.',
                'data' => null,
            ], 422);
        }
    }
}
