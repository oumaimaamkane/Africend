<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'code' , 'name' , 'lead_confirmed_fees' , 'lead_delivered_fees' , 'lead_returned_fees' ,'lead_canceled_fees' , 'currency'
    ];
}
