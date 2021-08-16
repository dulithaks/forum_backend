<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;
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
        try {
            $data = $request->validated();

            if (Auth::attempt($data)) {
                $user = auth()->user();
                $user->generateToken();

                return response()->json(['data' => $user]);
            } else {
                return response()->json([
                    'message' => 'The provided credentials do not match our records.',
                    'data' => null,
                ], 422);
            }
        } catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }
}
