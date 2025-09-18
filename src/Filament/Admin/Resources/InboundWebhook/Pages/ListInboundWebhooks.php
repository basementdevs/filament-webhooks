<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages;

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Filament\Admin\Widgets\InboundWebhookStatsByProviderPercentage;
use Basement\Webhooks\Filament\Admin\Widgets\InboundWebhookStatsBySource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class ListInboundWebhooks extends ListRecords
{
    protected static string $resource = InboundWebhookResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            InboundWebhookStatsBySource::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('source')
                    ->badge(),
                TextColumn::make('event'),
                TextColumn::make('created_at')
                    ->description(fn ($record) => $record->created_at->diffForHumans()),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
