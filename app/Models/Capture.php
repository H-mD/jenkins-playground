<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capture extends Model
{
    use HasFactory;

    protected $table = 'captures';
    protected $fillable = [
        'result',
        'image_url',
        'rate',
        'user_id',
        'fish_id'
    ];
}
