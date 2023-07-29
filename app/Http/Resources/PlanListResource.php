<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanListResource extends JsonResource
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
            'name' => $this->name,
            'pieceQuantity' => $this->pieceQuantity,
            'simplePieceQuantity' => $this->simplePieceQuantity,
            'difficultPieceQuantity' => $this->difficultPieceQuantity,
            'simplePieceValue' => 'R$ ' . number_format($this->simplePieceValue, 2, ',', '.'),
            'difficultPieceValue' => 'R$ ' . number_format($this->difficultPieceValue, 2, ',', '.'),
            'additionalSimplePieceValue' => 'R$ ' . number_format($this->additionalSimplePieceValue, 2, ',', '.'),
            'additionalDifficultPieceValue' => 'R$ ' . number_format($this->additionalDifficultPieceValue, 2, ',', '.'),
            'isActive' => $this->alterTextIsActive($this->isActive),
        ];
    }

    private function alterTextIsActive($isActive)
    {
        if ($isActive === 1) {
            return "Ativo";
        }
        return "Bloqueado";
    }
}
