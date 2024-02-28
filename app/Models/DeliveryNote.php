<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $table = 'delivery_notes';

    protected $fillable = ['reference', 'user_id' , 'type' , 'orders_tn' , 'nbr_orders'];

    protected $casts = [
        'orders_tn' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
