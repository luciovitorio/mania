<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientId',
        'userId',
        'link',
    ];

    public function clothins()
    {
        return $this->belongsToMany(Clothing::class, 'rol_clothins', 'rolId', 'clothinId');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
