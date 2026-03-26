<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages;

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Enums\InboundWebhookStatus;
use Basement\Webhooks\Events\InboundWebhookReceived;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Filament\Admin\Widgets\InboundWebhookStatsBySource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
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
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->options(InboundWebhookStatus::class)
                    ->multiple(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('replay')
                    ->icon(Heroicon::ArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Replay Webhook')
                    ->modalDescription('This will re-dispatch the InboundWebhookReceived event, causing all listeners to re-process this webhook.')
                    ->action(function (InboundWebhook $record): void {
                        $record->update([
                            'status' => InboundWebhookStatus::Processing,
                            'processed_at' => null,
                            'error_message' => null,
                        ]);

                        try {
                            InboundWebhookReceived::dispatch($record);

                            $record->update([
                                'status' => InboundWebhookStatus::Completed,
                                'processed_at' => now(),
                            ]);
                        } catch (\Throwable $e) {
                            $record->update([
                                'status' => InboundWebhookStatus::Failed,
                                'processed_at' => now(),
                                'error_message' => $e->getMessage(),
                            ]);

                            throw $e;
                        }
                    })
                    ->successNotificationTitle('Webhook replayed successfully'),
                ActionGroup::make([
                    Action::make('copy_payload')
                        ->label('Copy Payload')
                        ->icon(Heroicon::ClipboardDocument)
                        ->color('gray')
                        ->action(function (InboundWebhook $record, Action $action): void {
                            $action->sendSuccessNotification();
                        })
                        ->successNotificationTitle('Payload copied to clipboard')
                        ->extraAttributes(fn (InboundWebhook $record) => [
                            'x-on:click' => 'window.navigator.clipboard.writeText('.json_encode(json_encode($record->getAttribute('payload'), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)).')',
                        ]),
                    DeleteAction::make(),
                ])->icon(Heroicon::EllipsisVertical),
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
