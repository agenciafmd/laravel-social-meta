<?php

declare(strict_types=1);

namespace Agenciafmd\SocialMeta\Http\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SocialMeta extends Component
{
    public string $url;

    public function __construct(
        public string $title,
        public string $description = '',
        public string $type = 'website',
        public string $card = 'summary_large_image',
        public string $image = '',
        string $url = '',
        public string $author = 'Agência F&MD'
    ) {
        $this->url = $url ?: url()->current();
    }

    public function render(): View
    {
        return view('social-meta::components.social-meta');
    }
}
