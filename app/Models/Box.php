<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;
    protected $fillable = [
        'width',
        'height',
        'length',
        'user_id',
        'front_id'
    ];
     public function User()
    {
        return $this->belongoTo(User::class, 'user_id');
    }
}
