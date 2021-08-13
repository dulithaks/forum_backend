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
            $token = $user->createToken('auth_token')->plainTextToken;
            $data = array_merge($user->toArray(), [
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);

            return response()->json(['data' => $data], 201);
        } catch (ValidationException $e) {
            dd($e);
            // TODO Handle validation error messages
        } catch (Exception $e) {
            dd($e);
            // TODO unknown exception
        }
    }
}
