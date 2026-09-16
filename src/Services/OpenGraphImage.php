<?php

declare(strict_types=1);

namespace Agenciafmd\SocialMeta\Services;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\FontFactory;

final class OpenGraphImage
{
    public function generate(string $title = 'A cultura come a estratégia no café da manhã', string $url = 'https://fmd.ag/blog/minha-url-amigavel', string $type = 'facebook'): string
    {
        $path = "open-graph/{$type}/" . Str::slug($title) . '.png';
        if (! Storage::exists($path)) {
            Storage::put($path, (string) $this->build($title, $url, $type)->toPng());
        }

        return Storage::url($path);
    }

    public function render(string $title = 'A cultura come a estratégia no café da manhã', string $url = 'https://fmd.ag/blog/minha-url-amigavel', string $type = 'facebook'): Response
    {
        $data = $this->build($title, $url, $type)->toPng();
        $mime = $data->mediaType();

        return response($data)
            ->header('Content-Type', $mime);
    }

    private function build(string $title, string $url, string $type = 'facebook'): ImageInterface
    {
        $config = Arr::dot(config('social-meta'));
        $setting = fn (string $key): mixed => $config["{$type}.{$key}"] ?? $config["default.{$key}"];

        // cria e preenche o canvas
        $manager = new ImageManager(new Driver);
        $img = $manager->create($setting('card.width'), $setting('card.height'));
        $img->fill($setting('card.fill'));

        // insere a logo
        $img->place($setting('logo.path'), $setting('logo.position'), $setting('logo.x'), $setting('logo.y'));

        // insere o title
        $titleX = $setting('title.x');
        $titleY = $setting('title.y');
        $titleLineHeight = $setting('title.line_height');

        $lines = explode("\n", wordwrap($title, $setting('title.maxlength')));
        $titleY -= ((count($lines) - 1) * $titleLineHeight);

        foreach ($lines as $line) {
            $img->text($line, $titleX, $titleY, $this->font($setting, 'title'));

            $titleY += $titleLineHeight;
        }

        // insere a url
        $img->text($url, $setting('url.x'), $setting('url.y'), $this->font($setting, 'url'));

        return $img;
    }

    private function font(Closure $setting, string $section): Closure
    {
        return function (FontFactory $font) use ($setting, $section): void {
            $font->file($setting("{$section}.font.file"));
            $font->size($setting("{$section}.font.size"));
            $font->color($setting("{$section}.font.color"));
            $font->align($setting("{$section}.font.align"));
            $font->valign($setting("{$section}.font.valign"));
        };
    }
}
