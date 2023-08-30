<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rolClothin extends Model
{
    use HasFactory;

    protected $fillable = [
        'clothinId',
        'clothQuantity',
        'hangQuantity',
        'rolId'
    ];
}
