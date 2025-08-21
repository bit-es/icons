<?php

declare(strict_types=1);

namespace Bites\Icons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BitesIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('bites-icons', []);

            $factory->add('bites', array_merge(['path' => __DIR__.'/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bites-icons.php', 'bites-icons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/bites-icons'),
            ], 'bites-icons');

            $this->publishes([
                __DIR__.'/../config/bites-icons.php' => $this->app->configPath('bites-icons.php'),
            ], 'bites-icons-config');
        }
    }
}
