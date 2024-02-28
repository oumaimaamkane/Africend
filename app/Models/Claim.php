<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;
    protected $table = 'claims';

    protected $guarded = ['id'];
    protected $fillable = ['user_id','type' , 'country_id' , 'city' , 'message' , 'status'];

    public function country(){
        return $this->belongsTo(Country::class , 'country_id');
    }
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
