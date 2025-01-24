<?php

namespace Agenciafmd\SocialMeta\Http\Components;

use Illuminate\View\Component;

class SocialMeta extends Component
{
    public string $title;

    public string $description;

    public string $type;

    public string $card;

    public string $image;

    public $url;

    public $author;

    public function __construct(
        string $title,
        string $description = '',
        string $type = 'website',
        string $card = 'summary_large_image',
        string $image = '',
        string $url = '',
        string $author = 'Agência F&MD'
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->card = $card;
        $this->image = $image;
        $this->url = $url ?: url()->current();
        $this->author = $author;
    }

    public function render()
    {
        return view('agenciafmd/social-meta::components.social-meta');
    }
}