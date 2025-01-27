<?php

namespace Agenciafmd\SocialMeta\Providers;

use Agenciafmd\SocialMeta\Http\Components\SocialMeta;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadBladeComponents();

        $this->loadBladeDirectives();

        $this->loadBladeComposers();

        $this->loadViews();
    }

    public function register(): void
    {
        //
    }

    protected function loadBladeComponents(): void
    {
        Blade::component('social-meta', SocialMeta::class);
    }

    protected function loadBladeComposers(): void
    {
        //
    }

    protected function loadBladeDirectives(): void
    {
        //
    }

    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'social-meta');
    }
}
