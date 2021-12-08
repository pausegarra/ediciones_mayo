<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->getEmail(), 'password' => $request->getPassword()])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email',$request->getEmail())
            ->first();
        return ['token' => $user->createToken($request->getEmail())->plainTextToken];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
