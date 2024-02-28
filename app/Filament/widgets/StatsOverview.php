<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {  
                 
        $total_sellers =  User::whereHas('roles', function ($roleQuery) {
            $roleQuery->where('name', 'Seller');
        })->count();
       
        $total_orders = Order::count();
        $confirmed_orders = Order::where('status' , '=' , 'Confirmé')->count();
        $No_aswer_orders = Order::where('status' , '=' , 'Pas de réponse')->count();
        $canceled_orders = Order::where('status' , '=' , 'Annulé')->count();

        $waiting_for_delivery_orders = Order::where('status' , '=' , 'en attente de livraison')->count();
        $delivered_orders = Order::where('status' , '=' , 'Livré')->count();
        $rejected_orders = Order::where('status' , '=' , 'Rejeté')->count();
        
        return [
            Stat::make(__('filament-panels::pages/dashboard.stats.total_sellers') , $total_sellers)
            ->icon('heroicon-o-user'),
           

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
