<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = User::query();

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('cpf', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%")
                ->orWhere('profile', 'like', "%{$searchTerm}%");
        }

        $query->orderBy($sortField, $sortDirection);

        $users = $query->with(['address', 'branch'])->paginate($perPage);

        return UserListResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if ($request->email !== null) {
            $existingUser = User::where('email', $request->email)->orWhere('cpf', $request->cpf)->first();
        } else {
            $existingUser = User::where('cpf', $request->cpf)->first();
        }


        if ($existingUser) {
            $messageEmail = null;
            $messageCPF = null;

            if ($existingUser->email !== null && $existingUser->email === $request->email) {
                $messageEmail = 'Já existe um usuário cadastrado com esse e-mail';
            }

            if ($existingUser->cpf === $request->cpf) {
                $messageCPF = 'Já existe um usuário cadastrado com esse CPF';
            }

            return response()->json([
                'messageEmail' => $messageEmail,
                'messageCPF' => $messageCPF,
            ], Response::HTTP_CONFLICT);
        }

        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['address', 'branch']);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        // Verifica se já existe o email e/ou CPF cadastrado
        if ($request->email !== null) {
            $existingUser = User::where('email', $request->email)
                ->where('id', '!=', $user->id)
                ->first();
        } else {
            $existingUser = User::where('cpf', $request->cpf)
                ->where('id', '!=', $user->id)
                ->first();
        }


        if ($existingUser) {
            $messageEmail = null;
            $messageCPF = null;

            if ($existingUser->email !== null && $existingUser->email === $request->email) {
                $messageEmail = 'Já existe um usuário cadastrado com esse e-mail';
            }

            if ($existingUser->cpf === $request->cpf) {
                $messageCPF = 'Já existe um usuário cadastrado com esse CPF';
            }

            return response()->json([
                'messageEmail' => $messageEmail,
                'messageCPF' => $messageCPF,
            ], Response::HTTP_CONFLICT);
        }

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
