<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginUserRequest;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated();

        // Determine the login field: email, username, or phone
        $loginField = $request->filled('email') ? 'email' : ($request->filled('username') ? 'username' : 'phone');

        // Attempt to authenticate using the appropriate field and password
        if (!Auth::attempt([$loginField => $request->$loginField, 'password' => $request->password])) {
            return $this->error('Invalid credentials', 401);
        }

        // Retrieve the authenticated user
        $user = User::firstWhere($loginField, $request->$loginField);

        // Generate an API token for the authenticated user
        $token = $user->createToken("API Token for " . $user->$loginField, ['*'], now()->addMonth());

        return $this->ok(
            'Authenticated',
            [
                'token' => $token->plainTextToken,
                'user' => $user
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok('');
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success('User created successfully', statusCode: 201);
    }
}
