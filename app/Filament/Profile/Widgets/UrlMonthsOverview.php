<?php

namespace App\Filament\Profile\Widgets;

use App\Models\UrlAnalytic;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\DB;

class UrlMonthsOverview extends ChartWidget
{
    protected static ?string $heading = 'Monthly visits';

    protected static ?string $pollingInterval = null;

    // public $data;

    protected function getData(): array
    {

        $data = Trend::query(
            UrlAnalytic::join('urls', 'url_analytics.url_id', '=', 'urls.id')
                ->where('urls.user_id', auth()->id())
                ->groupBy(DB::raw('DATE(url_analytics.created_at)'))
                ->orderBy('url_analytics.created_at')
        )
            ->dateColumn('url_analytics.created_at')
            ->between(
                now()->startOfMonth(),
                now()->endOfMonth()
            )
            ->perDay()
            ->count();
        // dd($data);
        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data' => $data->map(fn ($value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
