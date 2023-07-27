<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'cpf' => ['required', 'numeric'],
            'password' => ['required']
        ]);

        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'O CPF e/ou a senha esta incorreta!'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->profile !== 'ADMIN') {
            Auth::logout();

            return response([
                'message' => 'Você não tem permissão para se autenticar como administrador!'
            ], 403);
        }

        $token = $user->createToken('main')->plainTextToken;
        $user = $request->user()->load(['branch', 'address']);

        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->tokens()->delete();

        return response('', 204);
    }

    public function getUser(Request $request)
    {
        $user = $request->user()->load('branch');
        return new UserResource($user);
    }
}
