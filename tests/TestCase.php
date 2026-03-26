<?php

namespace Basement\Webhooks\Tests;

use Basement\Webhooks\FilamentWebhookPlugin;
use Basement\Webhooks\Tests\Fixtures\User;
use Basement\Webhooks\WebhooksServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider;
use Filament\Panel;
use Filament\Support\SupportServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ViewErrorBag;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Basement\\Webhooks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        view()->share('errors', new ViewErrorBag);

        $this->setUpPanel();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            WebhooksServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('auth.providers.users.model', User::class);

        $app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__.'/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }
    }

    private function setUpPanel(): void
    {
        $panel = Panel::make()
            ->id('testing')
            ->default()
            ->path('admin')
            ->login()
            ->userMenu(false)
            ->databaseNotifications(false)
            ->plugin(FilamentWebhookPlugin::make());

        $panel->register();
        $panel->boot();

        Filament::setCurrentPanel($panel);
    }
}
