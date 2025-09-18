<?php

namespace Basement\Webhooks\Filament\Admin\Widgets;

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InboundWebhookStatsBySource extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return $this->getInboundWebhooksStats();
    }

    private function getInboundWebhooksStats(): array
    {
        $totalWebhooks = InboundWebhook::count();
        $stats = [];

        foreach (InboundWebhookSource::cases() as $source) {
            $count = InboundWebhook::where('source', $source->value)->count();
            $percentage = $totalWebhooks > 0 ? round(($count / $totalWebhooks) * 100, 2) : 0;
            $stats[] = Stat::make("{$source->name}","{$percentage}%")
                ->descriptionIcon($source->getIcon())
                ->description("{$count} de {$totalWebhooks} webhooks")
                ->color($source->getColor());
        }
        return $stats;
    }
}
