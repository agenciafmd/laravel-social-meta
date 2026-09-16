<?php

declare(strict_types=1);

namespace Agenciafmd\SocialMeta\Providers;

use Illuminate\Support\ServiceProvider;

final class SocialMetaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootProviders();

        $this->bootPublish();
    }

    public function register(): void
    {
        $this->registerConfigs();
    }

    private function bootProviders(): void
    {
        $this->app->register(BladeServiceProvider::class);
    }

    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/social-meta.php', 'social-meta');
    }

    private function bootPublish(): void
    {
        $this->publishes([
            __DIR__ . '/../../config' => base_path('config'),
        ], 'social-meta:config');

        $this->publishes([
            __DIR__ . '/../../resources/fonts' => storage_path('social-meta/fonts'),
        ], 'social-meta:assets');
    }
}
