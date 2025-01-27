<?php

namespace Agenciafmd\SocialMeta\Providers;

use Illuminate\Support\ServiceProvider;

class SocialMetaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->providers();

        $this->publish();
    }

    public function register(): void
    {
        $this->loadConfigs();
    }

    protected function providers(): void
    {
        $this->app->register(BladeServiceProvider::class);
    }

    protected function loadConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/social-meta.php', 'social-meta');
    }

    protected function publish(): void
    {
        $this->publishes([
            __DIR__ . '/../../config' => base_path('config'),
        ], 'social-meta:config');

        $this->publishes([
            __DIR__ . '/../../resources/fonts' => storage_path('social-meta/fonts'),
        ], 'social-meta:assets');
    }
}
