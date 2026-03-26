<?php

declare(strict_types=1);

namespace Basement\Webhooks\Database\Factories;

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Enums\InboundWebhookStatus;
use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class InboundWebhookFactory extends Factory
{
    protected $model = InboundWebhook::class;

    public function definition(): array
    {
        return [
            'source' => $this->faker->randomElement(InboundWebhookSource::cases()),
            'event' => $this->faker->randomElement([
                'signature.accepted', 'signature.rejected', 'signature.viewed', 'signature.signed',
                'mail.sent', 'mail.opened', 'mail.refused', 'mail.delivered', 'mail.reason',
            ]),
            'url' => $this->faker->url(),
            'payload' => $this->generatePayload(),
            'status' => $this->faker->randomElement(InboundWebhookStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    private function generatePayload(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'object' => 'webhook',
            'name' => $this->faker->word(),
            'format' => 'json',
            'event' => [
                'id' => $this->faker->uuid(),
                'object' => 'event',
                'organization' => $this->faker->randomNumber(8),
                'type' => 'signature.accepted',
                'data' => [
                    'public_id' => $this->faker->uuid(),
                    'object' => 'signature',
                    'user' => [
                        'name' => $this->faker->name(),
                        'company' => null,
                        'email' => $this->faker->safeEmail(),
                        'phone' => null,
                        'cpf' => $this->faker->numerify('###########'),
                        'cnpj' => null,
                        'birthday' => $this->faker->date(),
                    ],
                    'document' => (string) $this->faker->randomNumber(3),
                ],
                'previous_attributes' => [],
                'created_at' => Carbon::now()->toIso8601String(),
            ],
        ];
    }
}
