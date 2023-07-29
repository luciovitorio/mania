<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Requests\PlanRequest;
use App\Http\Resources\PlanListResource;
use App\Http\Resources\PlanResource;
use Illuminate\Http\Response;

class PlanController extends Controller
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

        $query = Plan::query();

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('pieceQuantity', 'like', "%{$searchTerm}%")
                ->orWhere('simplePieceQuantity', 'like', "%{$searchTerm}%")
                ->orWhere('difficultPieceQuantity', 'like', "%{$searchTerm}%")
                ->orWhere('isActive', 'like', "%{$searchTerm}%");
        }

        $query->orderBy($sortField, $sortDirection);

        $plans = $query->paginate($perPage);

        return PlanListResource::collection($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {
        $plan = Plan::where('name', $request->name)->first();
        if ($plan) {
            $message = 'Já existe um plano cadastrado com esse nome';
            return response()->json(['message' => $message], Response::HTTP_CONFLICT);
        }

        $plan = Plan::create($request->validated());
        return new PlanResource($plan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return new PlanResource($plan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, Plan $plan)
    {
        // Verifica se já existe o email e/ou CPF cadastrado
        if ($request->name !== null) {
            $existingPlanName = Plan::where('name', $request->name)
                ->where('id', '!=', $plan->id)
                ->first();
        }


        if ($existingPlanName) {
            $message = 'Já existe um plano cadastrado com esse nome';
            return response()->json(['message' => $message], Response::HTTP_CONFLICT);
        }

        $plan->update($request->validated());

        return new PlanResource($plan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->noContent();
    }
}
