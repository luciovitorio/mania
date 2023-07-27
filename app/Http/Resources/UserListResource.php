<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'email' => $this->email,
            'cpf' => $this->formatCPF($this->cpf),
            'profile' => $this->changeProfile($this->profile),
            'isActive' => $this->alterTextIsActive($this->isActive),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'address' => new AddressResource($this->whenLoaded('address'))
        ];
    }

    private function changeProfile($text)
    {
        if ($text === 'ADMIN') {
            $text = 'Administrador';
            return $text;
        }

        if ($text === 'PASSADEIRA') {
            $text = 'Passadeira';
            return $text;
        }

        if ($text === 'SUPERVISAO') {
            $text = 'Supervisão';
            return $text;
        }
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
}
