<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\Page;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;

class QrScan extends Page
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.livreur.resources.order-resources-resource.pages.qr-scan';
    protected static ?string $navigationLabel = 'Scann qrcode';

    // protected static ?string $navigationIcon = 'iconsax-lin-scan-barcode';
    protected static ?int $navigationSort = 5;



     public $status , $reference , $comment , $deliveryMan , $postponed_date;

    public function reference($reference){
        $this->reference=$reference;
    }
    public function comment($comment){
        $this->comment=$comment;
    }

    public function updateOrder(){
        $carbonDateTime ='';
        if(isset($this->postponed_date)){
            $carbonDateTime = Carbon::createFromFormat('Y-m-d\TH:i', $this->postponed_date);
        }
        $order = Order::where('reference' , '=' , $this->reference)->first();
        if($order->delivery_id == auth()->id()){
            $onlyOrder = Order::find($order->id);
            if(isset($carbonDateTime)){
                $onlyOrder->update([
                    'status' => $this->status,
                    'comment' => $this->comment,
                    'postponed_date' => $carbonDateTime,
                ]);
            }else{
                $onlyOrder->update([
                    'status' => $this->status,
                    'comment' => $this->comment,
                ]);
            }
            $this->dispatch('close-modal', id: 'myModal');

            $user = User::find($onlyOrder->user_id);
            $tn = $onlyOrder->reference;
            $status = $this->status;

            Notification::make()
            ->success()
            ->title('Changement de statut de commande')
            ->body("Le statut de la commande numéro #$tn a été mis à jour en $status")
            ->icon('heroicon-o-truck')
            ->sendToDatabase($user);

            Notification::make()
            ->title('Statut de commande mis à jour avec succès')
            ->success()
             ->send();
        }else{
            Notification::make()
                ->title("La commande ne vous appartient pas")
                ->danger()
                ->send();
        }
        
    }

    
}
