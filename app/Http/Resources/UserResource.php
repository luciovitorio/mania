<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile' => $this->profile,
            'dateOfBirth' => $this->dateOfBirth,
            'percent' => $this->percent,
            'isActive' => $this->isActive,
            'cpf' => $this->cpf,
            'branchId' => $this->branchId,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'address' => new AddressResource($this->whenLoaded('address'))
        ];
    }
}
