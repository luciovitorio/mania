<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'planId' => ['required', 'numeric'],
            'branchId' => ['required', 'numeric'],
            'name' => ['required', 'max:2000'],
            'cpf' => ['nullable', 'string'],
            'rg' => ['nullable', 'string'],
            'dateOfBirth' => ['nullable', 'date'],
            'email' => ['nullable', 'email'],
            'homePhone' => ['nullable', 'string'],
            'cellPhone' => ['nullable', 'string'],
            'collectionFrequency' => ['required', 'in:SEMANAL,QUINZENAL,MENSAL,AVULSO'],
            'collectionDay' => ['nullable', 'in:SEGUNDA,TERCA,QUARTA,QUINTA,SEXTA,SABADO,DOMINGO'],
            'collectionTime' => ['nullable', 'date_format:H:i:s'],
            'deliveryDay' => ['nullable', 'in:SEGUNDA,TERCA,QUARTA,QUINTA,SEXTA,SABADO,DOMINGO'],
            'deliveryTime' => ['nullable', 'date_format:H:i:s'],
            'collectionStart' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'deliveryFee' => ['required', 'boolean'],
            'deliveryAmount' => ['nullable', 'integer'],
            'dueDate' => ['nullable', 'numeric'],
            'isActive' => ['required', 'boolean'],
        ];
    }
}
