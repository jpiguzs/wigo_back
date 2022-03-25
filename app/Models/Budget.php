<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;
class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'total' , 'status', 'user_id', 'express', 'payment_methods'
    ];
    protected $appends = ['payment', 'statusname'];

    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function Stops() {
        return $this->hasMany(Stop::class);
    }
    public function getPaymentAttribute()
    {
        $obj = new stdClass();
        $obj->name="";
        $obj->icon="";
        $obj->isIcon =false;
        //return Carbon::parse($this->attributes['birthday'])->age;
        if($this->attributes['payment_methods'] ==1 ) {
            $obj->name="binance";
            $obj->icon="/binance.png";
        }
       if($this->attributes['payment_methods'] ==2 ) {
            $obj->name="paypal";
            $obj->icon="/paypal.jpeg";
        }
        if($this->attributes['payment_methods'] ==3 ) {
            $obj->name="Tarjeta de credito";
            $obj->icon="payment";
            $obj->isIcon=true;
        }
        if($this->attributes['payment_methods'] ==4 ) {
            $obj->name="Efectivo";
            $obj->icon="payments";
            $obj->isIcon=true;
        }
        if($this->attributes['payment_methods'] ==5 ) {
            $obj->name="Transferencias";
            $obj->icon="account_balance";
            $obj->isIcon=true;
        }
        return $obj;
    }
    public function getStatusnameAttribute(){
        if($this->attributes['status']===1){
            return "En proceso";
        }
        if($this->attributes['status']===2){
            return "Activa";
        }
        if($this->attributes['status']===3){
            return "Terminada";
        }
    }
}
