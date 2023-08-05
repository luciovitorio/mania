<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientListResource extends JsonResource
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
            'cpf' => $this->formatCPF($this->cpf),
            'cellPhone' => $this->formatPhoneNumber($this->cellPhone),
            'isActive' => $this->alterTextIsActive($this->isActive),
            'branch' => new BranchForClientTableResource($this->whenLoaded('branch')),
            'plan' => new PlanForClientTableResource($this->whenLoaded('plan')),
            'address' => new AddressForClientTableResource($this->whenLoaded('address'))
        ];
    }

    private function alterTextIsActive($isActive)
    {
        if ($isActive === 1) {
            return "Ativo";
        }
        return "Bloqueado";
    }

    private function formatCPF($cpf)
    {
        // Remove qualquer caractere não numérico do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se o CPF possui 11 dígitos
        if (strlen($cpf) !== 11) {
            return $cpf; // Retorna o CPF original se não tiver 11 dígitos
        }

        // Formata o CPF com pontos e hífen
        $formatedCPF = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);

        return $formatedCPF;
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
