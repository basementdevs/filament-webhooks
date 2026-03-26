<?php

declare(strict_types=1);

namespace Basement\Webhooks;

use BackedEnum;
use Basement\Webhooks\Contracts\InboundWebhookContract;
use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Models\InboundWebhook;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class FilamentWebhookPlugin implements Plugin
{
    private ?string $navigationGroup = null;

    private ?string $navigationLabel = null;

    private string|BackedEnum|Htmlable|null $navigationIcon = null;

    private string|BackedEnum|null $activeNavigationIcon = null;

    private ?int $navigationSort = null;

    /** @var class-string<InboundWebhook>|null */
    private ?string $model = null;

    /** @var class-string<InboundWebhookContract>|null */
    private ?string $providersEnum = null;

    private ?bool $viewAny = null;

    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-webhooks';
    }

    public function navigationGroup(string $group): self
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationLabel(string $label): self
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function navigationIcon(string|BackedEnum|Htmlable $icon): self
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function activeNavigationIcon(string|BackedEnum $icon): self
    {
        $this->activeNavigationIcon = $icon;

        return $this;
    }

    public function navigationSort(int $sort): self
    {
        $this->navigationSort = $sort;

        return $this;
    }

    /** @param class-string<InboundWebhook> $model */
    public function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /** @param class-string<InboundWebhookContract> $enum */
    public function providersEnum(string $enum): self
    {
        $this->providersEnum = $enum;

        return $this;
    }

    public function viewAny(bool $viewAny): self
    {
        $this->viewAny = $viewAny;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            InboundWebhookResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        if ($this->navigationGroup !== null) {
            config()->set('filament-webhooks.navigation_group', $this->navigationGroup);
        }

        if ($this->navigationLabel !== null) {
            config()->set('filament-webhooks.navigation_label', $this->navigationLabel);
        }

        if ($this->navigationIcon !== null) {
            config()->set('filament-webhooks.navigation_icon_inactive', $this->navigationIcon);
        }

        if ($this->activeNavigationIcon !== null) {
            config()->set('filament-webhooks.navigation_icon_active', $this->activeNavigationIcon);
        }

        if ($this->navigationSort !== null) {
            config()->set('filament-webhooks.navigation_sort', $this->navigationSort);
        }

        if ($this->model !== null) {
            config()->set('filament-webhooks.model', $this->model);
        }

        if ($this->providersEnum !== null) {
            config()->set('filament-webhooks.providers_enum', $this->providersEnum);
        }

        if ($this->viewAny !== null) {
            config()->set('filament-webhooks.view_any', $this->viewAny);
        }
    }

    public function getNavigationGroup(): string
    {
        return $this->navigationGroup ?? config('filament-webhooks.navigation_group', 'Logs');
    }

    public function getNavigationLabel(): string
    {
        return $this->navigationLabel ?? config('filament-webhooks.navigation_label', 'Webhooks');
    }

    public function getNavigationIcon(): string|BackedEnum|Htmlable
    {
        return $this->navigationIcon ?? config('filament-webhooks.navigation_icon_inactive', Heroicon::OutlinedBell);
    }

    public function getActiveNavigationIcon(): string|BackedEnum
    {
        return $this->activeNavigationIcon ?? config('filament-webhooks.navigation_icon_active', Heroicon::Bell);
    }

    public function getNavigationSort(): int
    {
        return $this->navigationSort ?? config('filament-webhooks.navigation_sort', 100);
    }

    /** @return class-string<InboundWebhook> */
    public function getModel(): string
    {
        return $this->model ?? config('filament-webhooks.model', InboundWebhook::class);
    }

    /** @return class-string<InboundWebhookContract> */
    public function getProvidersEnum(): string
    {
        return $this->providersEnum ?? config('filament-webhooks.providers_enum', InboundWebhookSource::class);
    }

    public function getViewAny(): bool
    {
        return $this->viewAny ?? config('filament-webhooks.view_any', true);
    }
}
