<?php

declare(strict_types=1);

use Basement\Webhooks\Contracts\InboundWebhookContract;
use Basement\Webhooks\Enums\InboundWebhookSource;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

describe('InboundWebhookSource', function (): void {

    it('implements required contracts', function (): void {
        $source = InboundWebhookSource::Resend;

        expect($source)->toBeInstanceOf(HasColor::class)
            ->and($source)->toBeInstanceOf(HasIcon::class)
            ->and($source)->toBeInstanceOf(HasLabel::class)
            ->and($source)->toBeInstanceOf(InboundWebhookContract::class);
    });

    it('has labels for all cases', function (): void {
        foreach (InboundWebhookSource::cases() as $case) {
            expect($case->getLabel())->toBeString()->not->toBeEmpty();
        }
    });

    it('has colors for all cases', function (): void {
        foreach (InboundWebhookSource::cases() as $case) {
            expect($case->getColor())->toBeArray();
        }
    });

    it('has icons for all cases', function (): void {
        foreach (InboundWebhookSource::cases() as $case) {
            expect($case->getIcon())->not->toBeNull();
        }
    });

    it('has correct string values', function (): void {
        expect(InboundWebhookSource::Resend->value)->toBe('resend')
            ->and(InboundWebhookSource::Autentique->value)->toBe('autentique');
    });
});
