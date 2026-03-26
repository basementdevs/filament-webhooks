<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Widgets;

use Basement\Webhooks\Models\InboundWebhook;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

final class InboundWebhookTimeline extends ChartWidget
{
    protected ?string $heading = 'Webhook Volume (Last 30 Days)';

    protected string $color = 'primary';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        /** @var class-string<InboundWebhook> $modelClass */
        $modelClass = config('filament-webhooks.model', InboundWebhook::class);

        $startDate = Carbon::now()->subDays(29)->startOfDay();

        $counts = $modelClass::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('M d');
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Webhooks',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
