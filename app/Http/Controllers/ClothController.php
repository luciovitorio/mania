<?php

namespace App\Http\Controllers;

use App\Models\Clothin;
use App\Http\Requests\ClothinRequest;
use App\Http\Resources\ClothListResource;
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

            $query->where(function ($q) use ($searchTerm, $search) {
                $q->whereRaw('REPLACE(name, " ", "") like ?', "%{$searchTerm}%")
                    ->orWhere('type', 'like', "%{$search}%")
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Clothin $clothin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClothinRequest $request, Clothin $clothin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clothin $clothin)
    {
        //
    }
}
