<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['user_id','country_id' ,'title' , 'description' , 'price' , 'image'  , 'initial_quantity'  , 'status'];

    protected $casts = [
        'image' => 'array'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
 
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
    
}
