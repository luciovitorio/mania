<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'planId' => $this->planId,
            'branchId' => $this->branchId,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'rg' => $this->rg,
            'dateOfBirth' => $this->dateOfBirth,
            'email' => $this->email,
            'homePhone' => $this->homePhone,
            'cellPhone' => $this->cellPhone,
            'collectionFrequency' => $this->collectionFrequency,
            'collectionDay' => $this->collectionDay,
            'collectionTime' => $this->collectionTime,
            'deliveryDay' => $this->deliveryDay,
            'deliveryTime' => $this->deliveryTime,
            'collectionStart' => $this->collectionStart,
            'description' => $this->description,
            'deliveryFee' => $this->deliveryFee,
            'deliveryAmount' => $this->deliveryAmount,
            'dueDate' => $this->dueDate,
            'isActive' => $this->isActive,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'address' => new AddressResource($this->whenLoaded('address'))
        ];
    }
}
