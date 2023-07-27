<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    public function clothins()
    {
        return $this->belongsToMany(Clothing::class, 'rol_clothins', 'rolId', 'clothinId');
    }
}
