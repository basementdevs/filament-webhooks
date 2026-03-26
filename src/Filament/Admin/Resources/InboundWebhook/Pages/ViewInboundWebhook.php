<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages;

use Basement\Webhooks\Enums\InboundWebhookStatus;
use Basement\Webhooks\Events\InboundWebhookReceived;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Actions\Action;
use Filament\Infolists\Components\CodeEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                        TextEntry::make('status')
                            ->badge()
                            ->label('Status'),
                        TextEntry::make('processed_at')
                            ->dateTime()
                            ->placeholder('Not processed yet')
                            ->label('Processed At'),
                    ]),
                TextEntry::make('error_message')
                    ->columnSpanFull()
                    ->color('danger')
                    ->visible(fn ($record) => $record->error_message !== null)
                    ->label('Error Message'),
                TextEntry::make('url')
                    ->columnSpanFull()
                    ->label('URL'),
                CodeEntry::make('payload')
                    ->state(fn ($record) => json_encode($record->payload, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))
                    ->grammar(Grammar::Json)
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('replay')
                ->icon(Heroicon::ArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Replay Webhook')
                ->modalDescription('This will re-dispatch the InboundWebhookReceived event, causing all listeners to re-process this webhook.')
                ->action(function (): void {
                    /** @var InboundWebhook $record */
                    $record = $this->record;

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
        ];
    }
}
