<?php

declare(strict_types=1);

namespace Agenciafmd\SocialMeta\Http\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SocialMeta extends Component
{
    public string $title;

    public string $description;

    public string $type;

    public string $card;

    public string $image;

    public string $url;

    public string $author;

    public function __construct(
        string $title,
        string $description = '',
        string $type = 'website',
        string $card = 'summary_large_image',
        string $image = '',
        string $url = '',
        string $author = 'Agência F&MD'
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->card = $card;
        $this->image = $image;
        $this->url = $url ?: url()->current();
        $this->author = $author;
    }

    public function render(): View
    {
        return view('social-meta::components.social-meta');
    }
}
