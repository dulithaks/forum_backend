<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'           => 'required|confirmed',
        ]);
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Success
            return redirect()->intended('/panel');
        } else {
            // Go back on error (or do what you want)
            return redirect()->back();
        }
    }
}
