<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClothListResource extends JsonResource
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
            'type' => $this->alterTextIsActive($this->type),
            'branch' => new BranchResource($this->whenLoaded('branch')),
        ];
    }

    private function alterTextIsActive($type)
    {
        if ($type === 'EASY') {
            return "FÁCIL";
        }
        return "DIFÍCIL";
    }
}
