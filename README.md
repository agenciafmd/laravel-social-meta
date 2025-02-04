# Laravel - Social Meta

[![Downloads](https://img.shields.io/packagist/dt/agenciafmd/laravel-social-meta.svg?style=flat-square)](https://packagist.org/packages/agenciafmd/laravel-social-meta)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

- Social Meta para quem não sabe para que serve

## Instalação

```
composer require agenciafmd/laravel-social-meta:v11.x-dev
```

## Uso

Dentro do seu `master.blade.php` (obrigado [Blade UI Kit](https://blade-ui-kit.com/docs/0.x/social-meta))

```blade
<x-social-meta
    title="{{ $__env->yieldContent('title', 'A cultura come a estratégia no café da manhã.') }} | {{ config('app.name') }}"
    description="{{ $__env->yieldContent('description') }}"
/>
```

Em seus filhos

```
@extends('agenciafmd/frontend::master')

@section('title', 'A cultura come a estratégia no café da manhã.')
@section('description', 'Esta é uma frase de Peter Drucker, considerado o pai da administração moderna.')
```

### Saída

```
<title>A cultura come a estratégia no café da manhã | Laravel</title>

<meta name="twitter:card" content="summary_large_image" />

<meta property="og:type" content="website">
<meta property="og:title" content="A cultura come a estratégia no café da manhã | Laravel" />

<meta name="description" content="Esta é uma frase de Peter Drucker, considerado o pai da administração moderna.">
<meta property="og:description" content="Esta é uma frase de Peter Drucker, considerado o pai da administração moderna.">

<meta property="og:image" content="http://starternovo.local/storage/open-graph/facebook/my-custom-title-laravel.png" />
<meta property="og:url" content="http://starternovo.local" />
<meta property="og:locale" content="pt_BR" />
<meta property="og:site_name" content="Laravel"/>
<meta name="author" content="Agência F&MD">
 
```

OpenGraph Imagem

![OpenGraph Image](https://raw.githubusercontent.com/agenciafmd/admix-social-meta/master/docs/screenshot.jpg "OpenGraph Image")

## Customização

```bash
php artisan vendor:publish --tag=social-meta:config
``` 

Publicação das fontes em `storage/social-meta`

```bash
php artisan vendor:publish --tag=social-meta:assets
```

> Não esqueça de customizar os paths em `config/social-meta.php`

## Debug

Coloque no `routes/web.php` e acesse `/debug`

```php
use Facades\Agenciafmd\SocialMeta\Services\OpenGraphImage;

Route::get('/debug', function() {
    return OpenGraphImage::render();
});
```

## Licença

Licença MIT. [Click here](LICENSE.md) para mais detalhes.
