<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'link' => $this->link,
            'created_at' => $this->created_at,
            'finishedAt' => $this->finishedAt,
            'userId' => $this->userId,
            'status' => $this->status,
            'client' => new ClientForRolTableResource($this->whenLoaded('client')),
            'user' => new UserForRolTableResource($this->whenLoaded('user')),
        ];
    }
}
