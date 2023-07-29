<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'pieceQuantity' => $this->pieceQuantity,
            'simplePieceQuantity' => $this->simplePieceQuantity,
            'difficultPieceQuantity' => $this->difficultPieceQuantity,
            'simplePieceValue' => $this->simplePieceValue,
            'difficultPieceValue' => $this->difficultPieceValue,
            'additionalSimplePieceValue' => $this->additionalSimplePieceValue,
            'additionalDifficultPieceValue' => $this->additionalDifficultPieceValue,
            'isActive' => $this->isActive,
        ];
    }
}
