<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function __invoke(Request $request)
    {
        $validation = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($validation)) {
            return (new UserResource("invalid credentials", null, []))
                ->response()
                ->setStatusCode(401);
        }

        $user = User::findOrFail(auth()->user()->id);
        return new UserResource("Success Login", $user->createToken($user->name)->plainTextToken, $user);
    }
}
