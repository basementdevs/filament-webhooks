<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Widgets;

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class InboundWebhookStatsBySource extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return $this->getInboundWebhooksStats();
    }

    private function getInboundWebhooksStats(): array
    {
        /** @var class-string<InboundWebhook> $modelClass */
        $modelClass = config('filament-webhooks.model', InboundWebhook::class);

        /** @var class-string<InboundWebhookSource> $enumClass */
        $enumClass = config('filament-webhooks.providers_enum', InboundWebhookSource::class);

        $counts = $modelClass::query()
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $totalWebhooks = $counts->sum();
        $stats = [];

        foreach ($enumClass::cases() as $source) {
            $count = $counts->get($source->value, 0);
            $percentage = $totalWebhooks > 0 ? round(($count / $totalWebhooks) * 100, 2) : 0;
            $stats[] = Stat::make($source->getLabel(), "{$percentage}%")
                ->descriptionIcon($source->getIcon())
                ->description("{$count} / {$totalWebhooks} webhooks")
                ->color($source->getColor());
        }

        return $stats;
    }
}
