<?php

namespace App\Filament\Profile\Widgets;

use App\Models\UrlAnalytic;
use Filament\Widgets\ChartWidget;

class UrlRefererOverview extends ChartWidget
{
    protected static ?string $heading = 'Referers';

    protected static ?string $pollingInterval = null;

    public ?string $filter = 'month';

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        if ($activeFilter === 'today') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        }
        if ($activeFilter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        }
        if ($activeFilter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }
        if ($activeFilter === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        $data = UrlAnalytic::join('urls', 'url_analytics.url_id', '=', 'urls.id')
            ->where('urls.user_id', auth()->id())
            ->whereDate('url_analytics.created_at', '>=', $startDate)
            ->whereDate('url_analytics.created_at', '<=', $endDate)
            ->where('url_analytics.referer', '!=', null)
            ->groupBy('url_analytics.referer')
            ->selectRaw('count(*) as referer_count, url_analytics.referer as referer')
            ->get();
        // dd($data);
        return [
            'datasets' => [
                [
                    'label' => 'Referers',
                    'data' => $data->map(fn ($value) => $value->referer_count),
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->referer),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
