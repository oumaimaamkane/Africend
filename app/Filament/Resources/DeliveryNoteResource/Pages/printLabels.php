<?php

namespace App\Filament\Resources\DeliveryNoteResource\Pages;

use App\Filament\Resources\DeliveryNoteResource;
use App\Models\Country;
use App\Models\DeliveryNote;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Illuminate\Foundation\Auth\User;

class printLabels extends Page
{
    protected static string $resource = DeliveryNoteResource::class;

    protected static string $view = 'filament.resources.orders-resource.pages.print-labels';

    public $number , $delivery_note ,$orders , $country , $user ; 


    public function mount($number , $record)
    {
        $this->number = $number; // Set the value of the parameter
        $delivery_note = DeliveryNote::find($record);
            $orders = array();
            $user = User::find($delivery_note->user_id);
            $country =new Country();
            foreach (json_decode($delivery_note->orders_tn) as $key){
                $order = Order::where('id' , '=' , $key)
                ->first();
                $country = Country::where('id' , '=' , $order->country_id)->first();
                array_push($orders , $order);
        }
        $this->delivery_note = $delivery_note;
        $this->orders = $orders;
        $this->country = $country;
        $this->user = $user;
    }

}
