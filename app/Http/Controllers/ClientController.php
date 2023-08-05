<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientListResource;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', false);
        $perpage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Client::query();
        $query->orderBy($sortField, $sortDirection);

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $query->where(function ($q) use ($searchTerm, $search) {
                $q->whereRaw('REPLACE(name, " ", "") like ?', "%{$searchTerm}%")
                    ->orWhere('cellphone', 'like', "%{$search}%")
                    ->orWhere('homephone', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhereHas('plan', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('address', function ($q) use ($search) {
                        $q->where('cep', 'like', "%{$search}%")
                            ->orWhere('street', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%");
                    });
            });
        }

        $clients = $query->with(['address', 'branch', 'plan'])->paginate($perpage);

        return ClientListResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        if ($request->email !== null) {
            $existingClient = Client::where('email', $request->email)->orWhere('cpf', $request->cpf)->first();
        } else if ($request->cpf !== null) {
            $existingClient = Client::where('cpf', $request->cpf)->first();
        }

        if (isset($existingClient)) {
            $messageEmail = null;
            $messageCPF = null;

            if ($existingClient->email !== null && $existingClient->email === $request->email) {
                $messageEmail = 'Já existe um usuário cadastrado com esse e-mail';
            }

            if ($existingClient->cpf === $request->cpf) {
                $messageCPF = 'Já existe um usuário cadastrado com esse CPF';
            }

            return response()->json([
                'messageEmail' => $messageEmail,
                'messageCPF' => $messageCPF,
            ], Response::HTTP_CONFLICT);
        }

        $client = Client::create($request->validated());
        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['address', 'branch']);
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        // Verifica se já existe o email e/ou CPF cadastrado
        if ($request->email !== null) {
            $existingClient = Client::where('email', $request->email)
                ->where('id', '!=', $client->id)
                ->first();
        } else {
            $existingClient = Client::where('cpf', $request->cpf)
                ->where('id', '!=', $client->id)
                ->first();
        }


        if ($existingClient) {
            $messageEmail = null;
            $messageCPF = null;

            if ($existingClient->email !== null && $existingClient->email === $request->email) {
                $messageEmail = 'Já existe um usuário cadastrado com esse e-mail';
            }

            if ($existingClient->cpf === $request->cpf) {
                $messageCPF = 'Já existe um usuário cadastrado com esse CPF';
            }

            return response()->json([
                'messageEmail' => $messageEmail,
                'messageCPF' => $messageCPF,
            ], Response::HTTP_CONFLICT);
        }

        $client->update($request->validated());

        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->noContent();
    }
}
