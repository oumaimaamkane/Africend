<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invc extends Model
{
    use HasFactory;
    
    protected $table = 'invoices';
    protected $fillable = ['reference','user_id' ,'orders_ids' , 'nbr_orders' , 'amount' ,'amount_net' , 'status', ];

    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
