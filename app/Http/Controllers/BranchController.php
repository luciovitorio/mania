<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchListResource;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Response;

class BranchController extends Controller
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

        $query = Branch::query();
        $query->orderBy($sortField, $sortDirection);

        if ($search) {
            $searchTerm = str_replace(' ', '', $search);

            $query->where(function ($q) use ($searchTerm, $search) {
                $q->whereRaw('REPLACE(name, " ", "") like ?', "%{$searchTerm}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhereHas('address', function ($q) use ($search) {
                        $q->where('cep', 'like', "%{$search}%")
                            ->orWhere('street', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%");
                    });
            });
        }

        $branches = $query->with('address')->paginate($perpage);

        return BranchListResource::collection($branches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {
        $branch = Branch::where('email', $request->email)->first();
        if ($branch) {
            $message = 'Já existe uma filial cadastrada com esse e-mail';
            return response()->json(['message' => $message], Response::HTTP_CONFLICT);
        }

        $branch = Branch::create($request->validated());
        return new BranchResource($branch);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $branch->load('address');
        return new BranchResource($branch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, Branch $branch)
    {
        // Verifica se o email fornecido é diferente do email atual da filial
        if ($request->email !== $branch->email) {
            $existingBranch = Branch::where('email', $request->email)->first();
            if ($existingBranch) {
                $message = 'Já existe uma filial cadastrada com esse e-mail';
                return response()->json(['message' => $message], Response::HTTP_CONFLICT);
            }
        }

        $branch->update($request->validated());

        return new BranchResource($branch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch): Response
    {
        $branch->delete();

        return response()->noContent();
    }
}
