<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowedPost extends Model
{
    use HasFactory;

    protected $table = 'followed_posts';
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
