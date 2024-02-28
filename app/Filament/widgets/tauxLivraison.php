<?php

namespace App\Filament\Seller\Widgets;
use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class tauxLivraison extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'tauxLivraison';
    protected static ?int $sort = 4;

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Taux de livraison';
    public ?string $filter = 'all';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        
        if($this->filter !== 'all'){
            $startDate = null;
            $endDate = null;
            switch ($this->filter) {
                case 'this week':
                    $startDate = now()->startOfWeek();
                    $endDate = now()->endOfWeek();
                    break;
                case 'last week':
                    $startDate = now()->subWeek()->startOfWeek();
                    $endDate = now()->subWeek()->endOfWeek();
                    break;
                case 'this month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'last month':
                    $startDate = now()->subMonth()->startOfMonth();
                    $endDate = now()->subMonth()->endOfMonth();
                    break;
                case 'this year':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
                default:
                    // Handle invalid filter value here
                    break;
            }

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
            $percentage ='';
            if($orders > 0){
                $orders_delivered = Order::where('status' , '=' ,'Livré')                
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                $percentage = ($orders_delivered / $orders)*100;
            }else{
                $orders_delivered = Order::where('status' , '=' ,'Livré')               
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                $percentage = ($orders_delivered / 1)*100;
            }
            return [
                'chart' => [
                    'type' => 'radialBar',
                    'height' => 300,
                ],
                'series' => [number_format($percentage , 2)],
                'plotOptions' => [
                    'radialBar' => [
                        'hollow' => [
                            'size' => '70%',
                        ],
                        'dataLabels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                                'fontFamily' => 'inherit'
                            ],
                            'value' => [
                                'show' => true,
                                'fontFamily' => 'inherit',
                                'fontWeight' => 600,
                                'fontSize' => '20px'
                            ],
                        ],
    
                    ],
                ],
                'stroke' => [
                    'lineCap' => 'round',
                ],
                'labels' => ['Delivery Rate'],
                'colors' => ['#f59e0b'],
            ];
        }else{  

            $orders = Order::count();

            $percentage ='';
            if($orders > 0){
                $orders_delivered = Order::where('status' , '=' ,'Livré')                
                ->count();
                $percentage = ($orders_delivered / $orders)*100;
            }else{
                $orders_delivered = Order::where('status' , '=' ,'Livré')               
                ->count();
                $percentage = ($orders_delivered / 1)*100;
            }
            return [
                'chart' => [
                    'type' => 'radialBar',
                    'height' => 300,
                ],
                'series' => [number_format($percentage , 2)],
                'plotOptions' => [
                    'radialBar' => [
                        'hollow' => [
                            'size' => '70%',
                        ],
                        'dataLabels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                                'fontFamily' => 'inherit'
                            ],
                            'value' => [
                                'show' => true,
                                'fontFamily' => 'inherit',
                                'fontWeight' => 600,
                                'fontSize' => '20px'
                            ],
                        ],
    
                    ],
                ],
                'stroke' => [
                    'lineCap' => 'round',
                ],
                'labels' => ['Delivery Rate'],
                'colors' => ['#f59e0b'],
            ];   
        }

    }
    protected function getFilters(): ?array
    {
        return [
            'all' => __('filament-panels::pages/dashboard.filters.all'),
            'this week' => __('filament-panels::pages/dashboard.filters.this week'),
            'last week' => __('filament-panels::pages/dashboard.filters.last week'),
            'this month' => __('filament-panels::pages/dashboard.filters.this month'),
            'last month' => __('filament-panels::pages/dashboard.filters.last month'),
            'this year' => __('filament-panels::pages/dashboard.filters.this year'),
        ];
    }
}
