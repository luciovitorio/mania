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

    public function setSimplePieceValueAttribute($value)
    {
        $this->attributes['simplePieceValue'] = $this->limparValorMoeda($value);
    }

    // Mutator para converter o campo "difficultPieceValue" para o formato numérico adequado antes de salvar no banco de dados
    public function setDifficultPieceValueAttribute($value)
    {
        $this->attributes['difficultPieceValue'] = $this->limparValorMoeda($value);
    }

    // Mutator para converter o campo "additionalSimplePieceValue" para o formato numérico adequado antes de salvar no banco de dados
    public function setAdditionalSimplePieceValueAttribute($value)
    {
        $this->attributes['additionalSimplePieceValue'] = $this->limparValorMoeda($value);
    }

    // Mutator para converter o campo "additionalDifficultPieceValue" para o formato numérico adequado antes de salvar no banco de dados
    public function setAdditionalDifficultPieceValueAttribute($value)
    {
        $this->attributes['additionalDifficultPieceValue'] = $this->limparValorMoeda($value);
    }

    private function limparValorMoeda($valor)
    {
        // Remover o símbolo "R$" e quaisquer outros caracteres não numéricos (como pontos e vírgulas)
        $valorLimpo = preg_replace("/[^0-9,]/", "", $valor);

        // Substituir a vírgula por ponto para garantir o formato correto de número decimal
        $valorDecimalFormatado = str_replace(',', '.', $valorLimpo);

        return $valorDecimalFormatado;
    }
}
