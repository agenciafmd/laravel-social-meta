<?php

declare(strict_types=1);

namespace Agenciafmd\SocialMeta\Tests\Feature\Services;

use Agenciafmd\SocialMeta\Services\OpenGraphImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('renders the card as a png image', function (): void {
    $response = new OpenGraphImage()->render('Um título longo o suficiente para quebrar em duas linhas no card', 'https://fmd.ag/blog');

    expect($response->headers->get('Content-Type'))->toBe('image/png')
        ->and(mb_substr((string) $response->getContent(), 1, 3, '8bit'))->toBe('PNG');
});

it('stores the card once and returns its url', function (): void {
    Storage::fake();

    $url = new OpenGraphImage()->generate('Título do artigo', 'https://fmd.ag/blog');

    Storage::assertExists('open-graph/facebook/titulo-do-artigo.png');
    expect($url)->toBe(Storage::url('open-graph/facebook/titulo-do-artigo.png'));
});
