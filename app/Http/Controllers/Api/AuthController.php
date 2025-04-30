<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;
use \App\Http\Resources\UserResource;
use App\Http\Requests\Api\Auth\RegisterRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        /*$validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);*/

        $validatedData = $request->validated();

        $subscriberRole = Role::where('name', 'subscriber')->first();

        $user = User::create([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'] ?? null,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => $subscriberRole?->id,
        ]);

         $token = $user->createToken('auth_token')->plainTextToken;
         return response()->json(['access_token' => $token, 'token_type' => 'Bearer', 'user' => new UserResource($user)], 201);

    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([

            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']])) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user = $request->user();

        $user->last_login_at = now();
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('role');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
