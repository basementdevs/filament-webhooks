<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages;

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Filament\Admin\Widgets\InboundWebhookStatsBySource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class ListInboundWebhooks extends ListRecords
{
    protected static string $resource = InboundWebhookResource::class;

    public function table(Table $table): Table
    {
        /** @var class-string<InboundWebhookSource> $enumClass */
        $enumClass = config('filament-webhooks.providers_enum', InboundWebhookSource::class);

        /** @var class-string<InboundWebhook> $modelClass */
        $modelClass = config('filament-webhooks.model', InboundWebhook::class);

        return $table
            ->columns([
                TextColumn::make('source')
                    ->badge()
                    ->searchable(),
                TextColumn::make('event')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('source')
                    ->options(
                        collect($enumClass::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    ),
                SelectFilter::make('event')
                    ->options(
                        fn () => $modelClass::query()
                            ->distinct()
                            ->pluck('event', 'event')
                            ->toArray()
                    ),
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

    protected function getHeaderWidgets(): array
    {
        return [
            InboundWebhookStatsBySource::make(),
        ];
    }
}
