<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages;

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Filament\Infolists\Components\CodeEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Phiki\Grammar\Grammar;

final class ViewInboundWebhook extends ViewRecord
{
    protected static string $resource = InboundWebhookResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('source')
                            ->badge()
                            ->label('Source'),
                        TextEntry::make('event')
                            ->label('Event'),
                    ]),
                TextEntry::make('url')
                    ->columnSpanFull()
                    ->label('URL'),
                CodeEntry::make('payload')
                    ->state(fn ($record) => json_encode(json_decode((string) $record->payload), JSON_PRETTY_PRINT))
                    ->grammar(Grammar::Json)
                    ->columnSpanFull(),
            ]);
    }
}
