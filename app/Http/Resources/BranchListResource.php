<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchListResource extends JsonResource
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
            'phone' => $this->formatPhone($this->phone),
            'whatsapp' => $this->formatCellphone($this->whatsapp),
            'address' => new AddressListResource($this->whenLoaded('address'))
        ];
    }

    private function formatPhone($phone)
    {
        if ($phone) {
            return '(' . substr($phone, 0, 2) . ') '
                . substr($phone, 2, 4) . '-'
                . substr($phone, 6);
        }
        return '-';
    }

    private function formatCellphone($cellphone)
    {
        if ($cellphone) {
            return '(' . substr($cellphone, 0, 2) . ') '
                . substr($cellphone, 2, 5) . '-'
                . substr($cellphone, 7);
        }
        return "-";
    }
}
