<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Http\Requests\RolRequest;
use App\Http\Requests\SendWzpRequest;
use App\Http\Resources\RolClientResource;
use App\Http\Resources\RolListResource;
use App\Http\Resources\RolResource;
use App\Models\System_Info;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class RolController extends Controller
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

        $query = Rol::query();
        $query->orderBy($sortField, $sortDirection);

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $query->where(function ($q) use ($searchTerm, $search) {
                $q->whereRaw('REPLACE(link, " ", "") like ?', "%{$searchTerm}%")
                    ->orWhere('finishedAt', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('cellPhone', 'like', "%{$search}%");
                    });
                $q->orWhereHas('client', function ($q) use ($search) {
                    $q->whereHas('branch', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $rols = $query->with(['client', 'user', 'client.branch'])->paginate($perPage);

        return RolListResource::collection($rols);
    }

    public function showForm()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolRequest $request)
    {
        $rol = Rol::create($request->validated());
        return new RolResource($rol);
    }

    /**
     * Send a new Message for WZP.
     */
    public function sendWzp(SendWzpRequest $request)
    {
        $rol = Rol::find($request->id);

        if ($rol) {
            $rol->status = 'ENVIADO';
            $rol->save(); //
        }

        return $rol;

        // // Recupere o token armazenado no banco de dados
        // $systemInfo = System_Info::find(1); // Recupere as informações da primeira linha (ou ajuste conforme necessário)
        // $facebookToken = $systemInfo->facebook_token;

        // // Montando os headers para a requisição POST
        // $headers = [
        //     'Authorization' => 'Bearer ' . $facebookToken,
        //     'Content-Type' => 'application/json',
        // ];

        // // Montando os dados a serem enviados no corpo da requisição
        // $postData = [
        //     'messaging_product' => 'whatsapp',
        //     'to' => '5521980376882', // Substitua pelo número de destino
        //     'type' => 'template',
        //     'template' => [
        //         'name' => 'hello_world',
        //         'language' => [
        //             'code' => 'en_US',
        //         ],
        //     ],
        // ];

        // // Fazendo a solicitação POST usando a biblioteca HTTP do Laravel
        // $response = Http::withHeaders($headers)
        //     ->post('https://graph.facebook.com/v17.0/117063934811059/messages', $postData);

        // // Lide com a resposta da solicitação como necessário
        // $responseData = $response->json();

        // // Retorne a resposta para a frontend ou realize qualquer outro processamento
        // return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     */
    public function show($rol)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolRequest $request, Rol $rol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        //
    }
}
