<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngridient extends Model
{
    use HasFactory;

    protected $table = 'recipe_ingridient';
    protected $fillable = [
        'recipe_id',
        'ingridient_id',
        'amount',
        'measurement'
    ];
}
