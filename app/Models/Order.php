<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use HasFactory , \OwenIt\Auditing\Auditable;

    
    protected $guarded = ['id'];

    protected $fillable = ['reference' , 'user_id', 'country_id' , 'product_id' , 'name' , 'number' , 'city','address' , 'quantity' , 
    'status' , 'tentative' ,'price' , 'confirmed_by' ,'assigned_to','delivery_id','in_bl' , 'comment' , 'postponed_date'];

  
    /**
     * Relations
     */

    public function user(){
        return  $this->belongsTo(User::class , 'user_id');
    }
    public function product(){
        return  $this->belongsTo(Product::class , 'product_id');
    }
    public function agent(){
        return  $this->belongsTo(User::class , 'assigned_to');
    }
    public function livreur(){
        return  $this->belongsTo(User::class , 'delivery_id');
    }

    public function country(){
        return  $this->belongsTo(Country::class);
    }

}
