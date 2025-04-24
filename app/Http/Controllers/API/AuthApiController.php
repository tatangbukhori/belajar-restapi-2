<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try {
            $request->validated();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->name),

            ]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ];
            return $this->sendResponse($data, 'Create Token Successfull!');
        } catch (Exception $error) {
            return $this->sendError('Create Token Failed!', $error->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return $this->sendError('Unauthorized', 'Authentication Failed!', 500);
            }

            // if hash doesn't match
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }


            // if success
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ];
            return $this->sendResponse($data, 'Authentication Success!');
        } catch (Exception $error) {
            return $this->sendError('Login Failed!', $error->getMessage(), 500);
        }
    }
    public function logout()
    {
        $user = User::find(Auth::user()->id);

        $user->tokens()->delete();

        return $this->sendResponse(null, 'Logout Successfull!');
    }
}
