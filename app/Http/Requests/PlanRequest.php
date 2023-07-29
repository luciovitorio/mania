<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'name' => ['required', 'max:255'],
            'pieceQuantity' => ['required', 'integer'],
            'simplePieceQuantity' => ['required', 'integer'],
            'difficultPieceQuantity' => ['required', 'integer'],
            'simplePieceValue' => ['required'],
            'difficultPieceValue' => ['required'],
            'additionalSimplePieceValue' => ['required'],
            'additionalDifficultPieceValue' => ['required'],
            'isActive' => ['required', 'boolean'],
        ];
    }
}
