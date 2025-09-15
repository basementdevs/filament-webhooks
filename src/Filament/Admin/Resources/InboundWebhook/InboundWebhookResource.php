<?php

declare(strict_types=1);

namespace Basement\Webhooks\Filament\Admin\Resources\InboundWebhook;

use BackedEnum;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages\ListInboundWebhooks;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages\ViewInboundWebhook;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

final class InboundWebhookResource extends Resource
{
    protected static ?string $model = InboundWebhook::class;

    protected static ?string $slug = 'inbound-webhooks';

    public static function getNavigationGroup(): UnitEnum|string|null
    {
        return config('filament-webhooks.navigation_group', 'Logs');
    }

    public static function getNavigationLabel(): string
    {
        return config('filament-webhooks.navigation_label', 'Webhooks');
    }

    public static function getLabel(): string
    {
        return config('filament-webhooks.navigation_label', 'Webhooks');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-webhooks.navigation_sort', 10);
    }

    public static function getModel(): string
    {
        return config('filament-webhooks.model', InboundWebhook::class);
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return config('filament-webhooks.navigation_icon_inactive', Heroicon::DocumentText);
    }

    public static function getActiveNavigationIcon(): BackedEnum|string|null
    {
        return config('filament-webhooks.navigation_icon_active', Heroicon::Bell);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInboundWebhooks::route('/'),
            'view' => ViewInboundWebhook::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
