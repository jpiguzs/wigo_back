<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_id' ,
    ];
    public function Budget()
    {
        return $this->belongoTo(Budget::class, 'budget_id');
    }

}
