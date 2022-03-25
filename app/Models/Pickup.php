<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;
    protected $fillable = ['stop_id', 'box_id', 'quantity'];

    public function Stop(){
       return $this->belongsTo(Stop::class, 'stop_id');
    }
    public function Box(){
      return  $this->belongsTo(Box::class, 'box_id');
    }
}
