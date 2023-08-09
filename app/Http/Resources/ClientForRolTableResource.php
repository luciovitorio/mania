<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientForRolTableResource extends JsonResource
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
            'cellPhone' => $this->formatPhoneNumber($this->cellPhone),
            'branchId' => $this->branchId,
            'branch' => new BranchForRolTableResource($this->whenLoaded('branch')),
        ];
    }

    function formatPhoneNumber($phoneNumber)
    {
        // Remove todos os caracteres não numéricos do número de telefone
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Verifica se o número de telefone tem 11 dígitos (com o DDD) ou 10 dígitos (sem DDD)
        if (strlen($phoneNumber) == 11) {
            // Formatação para números com DDD
            $formattedNumber = '(' . substr($phoneNumber, 0, 2) . ') ' . substr($phoneNumber, 2, 5) . '-' . substr($phoneNumber, 7);
        } elseif (strlen($phoneNumber) == 10) {
            // Formatação para números sem DDD
            $formattedNumber = '(' . substr($phoneNumber, 0, 2) . ') ' . substr($phoneNumber, 2, 4) . '-' . substr($phoneNumber, 6);
        } else {
            // Caso o número de telefone não tenha 10 ou 11 dígitos, retorna o número original
            $formattedNumber = $phoneNumber;
        }

        return $formattedNumber;
    }
}
