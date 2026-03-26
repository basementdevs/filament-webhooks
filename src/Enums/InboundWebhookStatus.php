<?php

declare(strict_types=1);

namespace Basement\Webhooks\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum InboundWebhookStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';

    case Processing = 'processing';

    case Completed = 'completed';

    case Failed = 'failed';

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Processing => 'info',
            self::Completed => 'success',
            self::Failed => 'danger',
        };
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Pending => Heroicon::Clock,
            self::Processing => Heroicon::ArrowPath,
            self::Completed => Heroicon::CheckCircle,
            self::Failed => Heroicon::XCircle,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
        };
    }
}
