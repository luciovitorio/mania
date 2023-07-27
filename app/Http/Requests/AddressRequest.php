<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'userId' => ['nullable', 'numeric'],
            'branchId' => ['nullable', 'numeric'],
            'clientId' => ['nullable', 'numeric'],
            'cep' => ['required', 'string'],
            'street' => ['required', 'string'],
            'number' => ['required', 'string'],
            'complement' => ['nullable', 'string'],
            'district' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
        ];
    }
}
