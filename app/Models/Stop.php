<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'previous_city_id',
        'total_pick',
        'total_stop',
        'total',
        'front_id',
        'budget_id'
    ];

    public function City() {
       return $this->belongsTo(Origin::class, 'city_id');
    }

    public function Previous_city(){
       return $this->belongsTo(Origin::class, 'previous_city_id');
    }
    public function Budget(){
       return $this->belongsTo(Budget::class, 'budget_id');
    }
    public function Deliverys(){
       return $this->hasMany(Delivery::class);
    }
    public function Pickups(){
      return  $this->hasMany(Pickup::class);
    }



}
