<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $fillable =[
        'origin_id', 'end_id', 'budget_id'
    ];

    public function Origin() {
        return $this->belongsTo(Origin::class, 'origin_id');
    }
    public function End() {
        return $this->belongsTo(Origin::class, 'end_id');
    }
}
