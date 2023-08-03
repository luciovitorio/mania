<?php

namespace App\Http\Controllers;

use App\Models\Clothin;
use App\Http\Requests\ClothinRequest;
use App\Http\Resources\ClothListResource;
use App\Http\Resources\ClothResource;
use Illuminate\Http\Response;

class ClothController extends Controller
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

        $query = Clothin::query();

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $typeMapping = [
                'facil' => 'EASY',
                'dificil' => 'HARD',
            ];

            $searchType = array_key_exists(strtolower($search), $typeMapping)
                ? $typeMapping[strtolower($search)]
                : $search;

            $query->where(function ($q) use ($searchTerm, $searchType, $search) {
                $q->whereRaw('REPLACE(name, " ", "") like ?', "%{$searchTerm}%")
                    ->orWhere('type', 'like', "%{$searchType}%")
                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }


        if ($sortField === 'branch') {
            $query->join('branches', 'clothins.branchId', '=', 'branches.id')
                ->select('clothins.*')
                ->orderBy('branches.name', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }


        $clothes = $query->with('branch')->paginate($perPage);

        return ClothListResource::collection($clothes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClothinRequest $request)
    {
        $existingCloth = Clothin::where('name', $request->name)->where('branchId', $request->branchId)->first();

        if ($existingCloth) {
            $message = 'Já existe uma peça de roupa cadastrada com esse nome para essa loja';
            return response()->json(['message' => $message], Response::HTTP_CONFLICT);
        }

        $cloth = Clothin::create($request->validated());
        return new ClothResource($cloth);
    }

    /**
     * Display the specified resource.
     */
    public function show(Clothin $cloth)
    {
        $cloth->load('branch');
        return new ClothResource($cloth);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClothinRequest $request, Clothin $cloth)
    {
        $existingCloth = Clothin::where('name', $request->name)
            ->where('id', '!=', $cloth->id)
            ->where('branchId', $request->branchId)
            ->first();

        if ($existingCloth) {
            $message = 'Já existe uma peça de roupa cadastrada com esse nome para essa loja';
            return response()->json(['message' => $message], Response::HTTP_CONFLICT);
        }

        $cloth->update($request->validated());

        return new ClothResource($cloth);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clothin $cloth)
    {
        $cloth->delete();

        return response()->noContent();
    }
}
