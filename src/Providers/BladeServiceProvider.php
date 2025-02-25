<?php

namespace Agenciafmd\SocialMeta\Providers;

use Agenciafmd\SocialMeta\Http\Components\SocialMeta;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootBladeComponents();

        $this->bootBladeDirectives();

        $this->bootBladeComposers();

        $this->bootViews();
    }

    public function register(): void
    {
        //
    }

    protected function bootBladeComponents(): void
    {
        Blade::component('social-meta', SocialMeta::class);
    }

    protected function bootBladeComposers(): void
    {
        //
    }

    protected function bootBladeDirectives(): void
    {
        //
    }

    protected function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'social-meta');
    }
}
