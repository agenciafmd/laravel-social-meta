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

        return response((string) $data)
            ->header('Content-Type', $mime);
    }

    private function build(string $title, string $url, string $type = 'facebook'): ImageInterface
    {
        $config = Arr::dot(config()->array('social-meta'));
        $setting = static fn (string $key): mixed => $config["{$type}.{$key}"] ?? $config["default.{$key}"] ?? null;
        $string = static fn (string $key): string => is_scalar($value = $setting($key)) ? (string) $value : '';
        $integer = static fn (string $key): int => is_numeric($value = $setting($key)) ? (int) $value : 0;
        $float = static fn (string $key): float => is_numeric($value = $setting($key)) ? (float) $value : 0.0;

        // cria e preenche o canvas
        $manager = new ImageManager(new Driver);
        $img = $manager->create($integer('card.width'), $integer('card.height'));
        $img->fill($setting('card.fill'));

        // insere a logo
        $img->place($string('logo.path'), $string('logo.position'), $integer('logo.x'), $integer('logo.y'));

        // insere o title
        $titleX = $integer('title.x');
        $titleY = $integer('title.y');
        $titleLineHeight = $integer('title.line_height');

        $lines = explode("\n", wordwrap($title, $integer('title.maxlength')));
        $titleY -= ((count($lines) - 1) * $titleLineHeight);

        foreach ($lines as $line) {
            $img->text($line, $titleX, $titleY, $this->font($string, $float, $setting, 'title'));

            $titleY += $titleLineHeight;
        }

        // insere a url
        $img->text($url, $integer('url.x'), $integer('url.y'), $this->font($string, $float, $setting, 'url'));

        return $img;
    }

    /**
     * @param  Closure(string): string  $string
     * @param  Closure(string): float  $float
     * @param  Closure(string): mixed  $setting
     */
    private function font(Closure $string, Closure $float, Closure $setting, string $section): Closure
    {
        return static function (FontFactory $font) use ($string, $float, $setting, $section): void {
            $font->file($string("{$section}.font.file"));
            $font->size($float("{$section}.font.size"));
            $font->color($setting("{$section}.font.color"));
            $font->align($string("{$section}.font.align"));
            $font->valign($string("{$section}.font.valign"));
        };
    }
}
