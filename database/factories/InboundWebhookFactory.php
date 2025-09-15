<?php

declare(strict_types=1);

namespace Basement\Webhooks\Database\Factories;

use Basement\Webhooks\Enums\InboundWebhookSource;
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
            'payload' => $this->generateJsonElement(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    private function generateJsonElement(): string
    {
        return json_encode([
            'id' => 'MjA5MnxlNTA5ZDMzOC0xYTg4LTQwMTYtYjM2Zi01N2Y1NzAyMDdmMGE=',
            'object' => 'webhook',
            'name' => 'teste',
            'format' => 'json',
            'event' => [
                'id' => 'e509d338-1a88-4016-b36f-57f570207f0a',
                'object' => 'event',
                'organization' => 15060912,
                'type' => 'signature.accepted',
                'data' => [
                    'public_id' => 'ecfd1908-61a9-11f0-bf9a-42010a2b600c',
                    'object' => 'signature',
                    'user' => [
                        'name' => 'Daniel Reis',
                        'company' => null,
                        'email' => 'daniel@3pontos.com',
                        'phone' => null,
                        'cpf' => '35061134885',
                        'cnpj' => null,
                        'birthday' => '1999-03-08',
                    ],
                    'document' => '123',

                ],
                'previous_attributes' => [],
                'created_at' => '2025-07-15T18:37:28.098812Z',
            ],
        ]);
    }
}
