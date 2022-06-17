<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=[
        'session_id',
        'description',
        'medias',
        'likes',
        'isPremium',
        'user_id',
    ];

}
