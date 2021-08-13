<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Exception;
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

            $user = User::create($data);
            $user->generateToken();

            return response()->json(['data' => $user->toArray()], 201);
        }
        catch (ValidationException $e) {
            dd($e);
            // TODO Handle validation error messages
        }
        catch (Exception $e) {
            dd($e);
            // TODO unknown exception
        }
    }
}
