<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisteredChemical extends Model
{
    protected $fillable = [
        'chemical_name', 'empirical_formula', 'CAS_number',
    ];
}
