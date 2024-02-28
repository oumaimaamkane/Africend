<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TauxConfirmation extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'tauxConfirmation';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Taux de confirmation';
    public ?string $filter = 'all';
    protected static ?int $sort = 3;

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    function __construct() {
        self::$heading = __('filament-panels::pages/dashboard.charts.confirmation_rate');
    }
    public function getHeading():?string{

        return self::$heading = __('filament-panels::pages/dashboard.charts.confirmation_rate');
     }
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
            $leads=Order::where('user_id' , '=' , auth()->id())->whereBetween('created_at', [$startDate, $endDate])->count();

            $percentage ='';
            if($leads > 0){
                $leads_confirmed=Order::where('user_id' , '=' , auth()->id())->where('status' , '=' ,'Confirmé')                
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                $percentage = ($leads_confirmed / $leads)*100;
            }else{
                $leads_confirmed=Order::where('user_id' , '=' , auth()->id())->where('status' , '=' ,'Confirmé')                
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                $percentage = ($leads_confirmed / 1)*100;
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
                    'labels' => ['Confirmation Rate'],
                    'colors' => ['#f59e0b'],
                ];
        }else{  
            $leads=Order::count();

            $percentage ='';
            if($leads > 0){
                $leads_confirmed=Order::where('user_id' , '=' , auth()->id())->where('status' , '=' ,'Confirmé')                
                ->count();
                $percentage = ($leads_confirmed / $leads)*100;
            }else{
                $leads_confirmed=Order::where('user_id' , '=' , auth()->id())->where('status' , '=' ,'Confirmé')                
                ->count();
                $percentage = ($leads_confirmed / 1)*100;
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
                'labels' => ['Confirmation Rate'],
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
