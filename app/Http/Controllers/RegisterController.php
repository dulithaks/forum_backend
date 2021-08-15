<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    /**
     * User register
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            $user->generateToken();

            return response()->json(['data' => $user], 201);
        } catch (Exception $e) {
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }
}
