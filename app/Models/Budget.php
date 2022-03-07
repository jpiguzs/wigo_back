<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'total' , 'status', 'user_id'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
