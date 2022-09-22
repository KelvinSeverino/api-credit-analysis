<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cpf', 'salary', 'limit_card', 'rent_value', 'street', 'street_number', 'county', 'state', 'cep'
    ];
}
