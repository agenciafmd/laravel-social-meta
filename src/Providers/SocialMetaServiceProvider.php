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
        $this->mergeConfigFrom(__DIR__ . '/../config/admix-social-meta.php', 'admix-social-meta');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../config' => base_path('config'),
        ], 'admix-social-meta:config');

        $this->publishes([
            __DIR__ . '/../storage/admix-social-meta' => storage_path('admix-social-meta'),
        ], 'admix-social-meta:assets');
    }
}
