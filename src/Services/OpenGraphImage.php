<?php

namespace Agenciafmd\SocialMeta\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class OpenGraphImage
{
    public function generate(string $title = 'A cultura come a estratégia no café da manhã', string $url = 'https://fmd.ag/blog/minha-url-amigavel', string $type = 'facebook')
    {
        $path = "open-graph/{$type}/" . Str::slug($title) . ".png";
        if (!Storage::exists($path)) {
            Storage::put($path, (string) $this->build($title, $url, $type)
                ->encode('png'));
        }

        return Storage::url($path);
    }

    public function render(string $title = 'A cultura come a estratégia no café da manhã', string $url = 'https://fmd.ag/blog/minha-url-amigavel', string $type = 'facebook')
    {
        return $this->build($title, $url, $type)
            ->response();
    }

    private function build(string $title, string $url, string $type = 'facebook')
    {
        $config = Arr::dot(config('social-meta'));

        // cria o canvas
        $cardWidth = $config["{$type}.card.width"] ?? $config['default.card.width'];
        $cardHeight = $config["{$type}.card.height"] ?? $config['default.card.height'];
        $img = Image::canvas($cardWidth, $cardHeight);

        // preenche o canvas
        $cardFill = $config["{$type}.card.fill"] ?? $config['default.card.fill'];
        $img->fill($cardFill);

        // insere a logo
        $logoPath = $config["{$type}.logo.path"] ?? $config['default.logo.path'];
        $logoPosition = $config["{$type}.logo.position"] ?? $config['default.logo.position'];
        $logoX = $config["{$type}.logo.x"] ?? $config['default.logo.x'];
        $logoY = $config["{$type}.logo.y"] ?? $config['default.logo.y'];
        $img->insert($logoPath, $logoPosition, $logoX, $logoY);

//        // insere linha
//        for($i=0; $i<=2; $i++) {
//            $img->line(30, 230 + $i, 100, 230 + $i, function ($draw) {
//                $draw->color('#191919');
//            });
//        }

        // insere o title
        $titleX = $config["{$type}.title.x"] ?? $config['default.title.x'];
        $titleY = $config["{$type}.title.y"] ?? $config['default.title.y'];
        $titleMaxlength = $config["{$type}.title.maxlength"] ?? $config['default.title.maxlength'];
        $titleLineHeight = $config["{$type}.title.line_height"] ?? $config['default.title.line_height'];
        $titleFontFile = $config["{$type}.title.font.file"] ?? $config['default.title.font.file'];
        $titleFontSize = $config["{$type}.title.font.size"] ?? $config['default.title.font.size'];
        $titleFontColor = $config["{$type}.title.font.color"] ?? $config['default.title.font.color'];
        $titleFontAlign = $config["{$type}.title.font.align"] ?? $config['default.title.font.align'];
        $titleFontValign = $config["{$type}.title.font.valign"] ?? $config['default.title.font.valign'];

        $lines = explode("\n", wordwrap($title, $titleMaxlength));
        $titleY = $titleY - ((count($lines) - 1) * $titleLineHeight);

        foreach ($lines as $line) {

            $img->text($line, $titleX, $titleY, function ($font) use (
                $titleFontFile, $titleFontSize, $titleFontColor, $titleFontAlign, $titleFontValign
            ) {
                $font->file($titleFontFile);
                $font->size($titleFontSize);
                $font->color($titleFontColor);
                $font->align($titleFontAlign);
                $font->valign($titleFontValign);
            });

            $titleY += $titleLineHeight;
        }

        // insere a url
        $urlX = $config["{$type}.url.x"] ?? $config['default.url.x'];
        $urlY = $config["{$type}.url.y"] ?? $config['default.url.y'];
        $urlFontFile = $config["{$type}.url.font.file"] ?? $config['default.url.font.file'];
        $urlFontSize = $config["{$type}.url.font.size"] ?? $config['default.url.font.size'];
        $urlFontColor = $config["{$type}.url.font.color"] ?? $config['default.url.font.color'];
        $urlFontAlign = $config["{$type}.url.font.align"] ?? $config['default.url.font.align'];
        $urlFontValign = $config["{$type}.url.font.valign"] ?? $config['default.url.font.valign'];

        $img->text($url, $urlX, $urlY, function ($font) use (
            $urlFontFile, $urlFontSize, $urlFontColor, $urlFontAlign, $urlFontValign
        ) {
            $font->file($urlFontFile);
            $font->size($urlFontSize);
            $font->color($urlFontColor);
            $font->align($urlFontAlign);
            $font->valign($urlFontValign);
        });

        return $img;
    }
}