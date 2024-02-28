<?php

namespace App\Filament\Seller\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Order;
use App\Models\Country;
use DB;
class OrdersChart extends ApexChartWidget
{
    protected static ?string $heading = 'Chart';

    public ?string $filter = 'all';
    protected static ?int $sort = 2;
    function __construct() {
        self::$heading = __('filament-panels::pages/dashboard.charts.orders_chart');
    }
    public function getHeading():?string{

        return self::$heading = __('filament-panels::pages/dashboard.charts.orders_chart');
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
            $leads = Order::select(
                DB::raw("CASE 
                            WHEN '$this->filter' = 'this year' THEN MONTHNAME(created_at) 
                            ELSE DATE(created_at) 
                        END as date"),
                DB::raw("COUNT(*) as count"))
                ->where('user_id' , '=' , auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw("CASE 
            WHEN '$this->filter' = 'this year' THEN MONTH(created_at) 
            ELSE DATE(created_at)
        END")
            ->groupBy('date')
            ->pluck('count', 'date');
            return [
                'chart' => [
                    'type' => 'line',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'OrdersChart',
                        'data' => $leads->values()->toArray(),
                    ],
                ],
                'xaxis' => [
                    'categories' => $leads->keys()->toArray(),
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'yaxis' => [
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'colors' => ['#f59e0b'],
                'stroke' => [
                    'curve' => 'smooth',
                ],
            ];
        }else{
            $leads = Order::select(
                DB::raw("CASE 
                            WHEN '$this->filter' = 'all' THEN MONTHNAME(created_at) 
                            ELSE DATE(created_at) 
                        END as date"),
                DB::raw("COUNT(*) as count"))
                ->where('user_id' , '=' , auth()->id())
            ->groupBy('date')
            ->orderByRaw("CASE 
            WHEN '$this->filter' = 'this year' THEN MONTH(created_at) 
            ELSE DATE(created_at)
        END")
            ->pluck('count', 'date');
            return [
                'chart' => [
                    'type' => 'line',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'OrdersChart',
                        'data' => $leads->values()->toArray(),
                    ],
                ],
                'xaxis' => [
                    'categories' => $leads->keys()->toArray(),
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'yaxis' => [
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
                'colors' => ['#f59e0b'],
                'stroke' => [
                    'curve' => 'smooth',
                ],
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

    protected function getType(): string
    {
        return 'line';
    }
}
