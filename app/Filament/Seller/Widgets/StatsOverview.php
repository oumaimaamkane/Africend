<?php

namespace App\Filament\Seller\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {  
       
        $total_orders = Order::count();
        $confirmed_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'Confirmé')->count();
        $No_aswer_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'Pas de réponse')->count();
        $canceled_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'Annulé')->count();

        $waiting_for_delivery_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'en attente de livraison')->count();
        $delivered_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'Livré')->count();
        $rejected_orders = Order::where('user_id' , '=' , auth()->id())->where('status' , '=' , 'Rejeté')->count();
        
        return [         

            Stat::make(__('filament-panels::pages/dashboard.stats.total_orders'), $total_orders)
            ->icon('heroicon-o-rectangle-stack'),

            Stat::make(__('filament-panels::pages/dashboard.stats.confirmed_leads'), $confirmed_orders)
            ->icon('heroicon-o-check-circle'),
            Stat::make(__('filament-panels::pages/dashboard.stats.no_answer_leads'), $No_aswer_orders)
            ->icon('heroicon-o-phone-x-mark'),
            Stat::make(__('filament-panels::pages/dashboard.stats.canceled_leads'), $canceled_orders)
            ->icon('heroicon-o-x-circle'),
            Stat::make(__('filament-panels::pages/dashboard.stats.rejected_leads'), $rejected_orders)
            ->icon('heroicon-o-minus-circle'),

            
            Stat::make(__('filament-panels::pages/dashboard.stats.waiting_fo_delivery_orders'), $waiting_for_delivery_orders)
            ->icon('heroicon-o-clock'),
            Stat::make(__('filament-panels::pages/dashboard.stats.delivered_orders'), $delivered_orders)
            ->icon('heroicon-o-truck'),

        ];
    
    }
}
