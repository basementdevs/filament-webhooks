<?php

declare(strict_types=1);

namespace Basement\Webhooks\Enums;

use BackedEnum;
use Basement\Webhooks\Contracts\InboundWebhookContract;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum InboundWebhookSource: string implements HasColor, HasIcon, HasLabel, InboundWebhookContract
{
    case Resend = 'resend';

    case Autentique = 'autentique';

    public function getColor(): array
    {
        return match ($this) {
            self::Resend => Color::Teal,
            self::Autentique => Color::Blue,
        };
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Resend => Heroicon::PaperAirplane,
            self::Autentique => Heroicon::DocumentText,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Resend => 'Resend',
            self::Autentique => 'Autentique',
        };
    }
}
