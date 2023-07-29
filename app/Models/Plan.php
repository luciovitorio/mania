<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pieceQuantity',
        'simplePieceQuantity',
        'difficultPieceQuantity',
        'simplePieceValue',
        'difficultPieceValue',
        'additionalSimplePieceValue',
        'additionalDifficultPieceValue',
        'isActive',
    ];

    // Accessor para formatar o campo "simplePieceValue" para o formato de moeda antes de retornar como JSON
    public function getSimplePieceValueAttribute($value)
    {
        return (float) $value;
    }

    // Accessor para formatar o campo "difficultPieceValue" para o formato de moeda antes de retornar como JSON
    public function getDifficultPieceValueAttribute($value)
    {
        return (float) $value;
    }

    // Accessor para formatar o campo "additionalSimplePieceValue" para o formato de moeda antes de retornar como JSON
    public function getAdditionalSimplePieceValueAttribute($value)
    {
        return (float) $value;
    }

    // Accessor para formatar o campo "additionalDifficultPieceValue" para o formato de moeda antes de retornar como JSON
    public function getAdditionalDifficultPieceValueAttribute($value)
    {
        // Converter o valor para número decimal (float)
        return (float) $value;
    }
}
