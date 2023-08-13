<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'planId',
        'branchId',
        'name',
        'cpf',
        'rg',
        'dateOfBirth',
        'homePhone',
        'cellPhone',
        'collectionFrequency',
        'collectionDay',
        'collectionTime',
        'deliveryDay',
        'deliveryTime',
        'collectionStart',
        'description',
        'deliveryFee',
        'deliveryAmount',
        'dueDate',
        'isActive'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branchId');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'clientId');
    }

    public function rol()
    {
        return $this->hasOne(Rol::class, 'clientId');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'planId');
    }
}
