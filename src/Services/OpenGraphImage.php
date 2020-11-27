<?php

namespace Agenciafmd\SocialMeta\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OpenGraphImage
{
    public function generate(string $text = 'A cultura come a estratégia no café da manhã', string $type = 'facebook')
    {
        $path = "open-graph/{$type}/" . Str::slug($text) . ".png";
        if (Storage::disk('public')
            ->exists($path)) {
            return Storage::url($path);
        }

        $patternPath = config("admix-social-meta.pattern");
        $fontPath = config("admix-social-meta.font");
        $fontColor = config("admix-social-meta.color");
        $config = config("admix-social-meta.type.{$type}");

        $width = $config['width'];
        $height = $config['height'];
        $centerX = ($config['centerX']) ?? intval($width / 2);
        $centerY = ($config['centerY']) ?? intval($height - ($height / 6));
        $maxLength = $config['maxLength'];
        $fontSize = $config['fontSize'];
        $lineHeight = $config['lineHeight'];
        $logo = $config['logo'];
        $centerImageY = intval($height / 5);

        $lines = explode("\n", wordwrap($text, $maxLength));
        $y = $centerY - ((count($lines) - 1) * $lineHeight);

        $img = Image::canvas($width, $height, '#f7f7f7');
        $img->fill($patternPath);
        foreach ($lines as $line) {
            $img->text($line, $centerX, $y, function ($font) use ($fontPath, $fontSize, $fontColor) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color($fontColor);
                $font->align('center');
                $font->valign('bottom');
            });

            $y += $lineHeight * 2;
        }

        $img->insert($logo, 'top', 0, $centerImageY);

        Storage::disk('public')
            ->put($path, $img->encode('png'));

        return Storage::url($path);
    }
}