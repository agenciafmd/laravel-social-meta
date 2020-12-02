<?php

namespace Agenciafmd\SocialMeta\Providers;

use Illuminate\Support\ServiceProvider;

class SocialMetaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->providers();

        $this->publish();
    }

    public function register()
    {
        $this->loadConfigs();
    }

    protected function providers()
    {
        $this->app->register(BladeServiceProvider::class);
    }

    protected function loadConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/social-meta.php', 'social-meta');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../config' => base_path('config'),
        ], 'social-meta:config');

        $this->publishes([
            __DIR__ . '/../storage/social-meta' => storage_path('social-meta'),
        ], 'social-meta:assets');
    }
}
