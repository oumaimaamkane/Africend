<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Str;
use Filament\Notifications\Notification;
class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function mutateFormDataBeforeCreate(array $data) :array{
        $refrence =  Str::random(3)."-". random_int(0 , 11111);

        $data['user_id'] = auth()->id();
        $data['reference'] = $refrence;

        return $data;
    }
    protected function afterCreate(){
        $admins = User::where('role_id' , '=' , 1)->get();
        $seller = User::find(auth()->id());
        //seller fullname
        $fullname = $seller->firstname .' ' . $seller->lastname;

        Notification::make()
            ->success()
            ->title('Nouveau Commande')
            ->body("Nouveau commande est ajouté par $fullname.")
            ->icon('heroicon-o-shopping-cart')
            ->sendToDatabase($admins);
    }
}
