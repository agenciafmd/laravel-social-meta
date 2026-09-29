# Laravel – Social Meta

[![Downloads](https://img.shields.io/packagist/dt/agenciafmd/laravel-social-meta.svg?style=flat-square)](https://packagist.org/packages/agenciafmd/laravel-social-meta)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Gera as meta tags de SEO / Open Graph / Twitter com um único componente Blade e, quando nenhuma imagem é informada, cria automaticamente a imagem de compartilhamento (Open Graph) com o logo, o título e a URL da página.

## Requisitos

- Laravel ^12.0 | ^13.0
- intervention/image ^3.11
- ext-fileinfo
- ext-imagick (a imagem é gerada com o driver Imagick do Intervention)

## Instalação

```bash
composer require agenciafmd/laravel-social-meta:dev-master
```

O service provider e o alias `OpenGraphImage` são registrados automaticamente (package discovery).

## Configuração

Publique o arquivo de configuração (`config/social-meta.php`):

```bash
php artisan vendor:publish --tag=social-meta:config
```

Nele ficam as dimensões e cores do card, a posição do logo e as fontes do título e da URL. As chaves de `facebook` sobrescrevem as de `default`.

| Chave | Padrão |
|---|---|
| `default.card` | `600` x `314`, fundo `#f7f7f7`, cor `#191919` |
| `default.logo.path` | `resource_path('images/logo.png')` |
| `default.title.font.file` | `open-sans-semi-bold.ttf` do pacote, tamanho `24` |
| `default.title.maxlength` | `48` caracteres por linha (quebra em várias linhas) |
| `default.url.font.file` | `open-sans-regular.ttf` do pacote, tamanho `14` |

> O logo em `resources/images/logo.png` precisa existir na aplicação.

Para customizar as fontes, publique-as em `storage/social-meta/fonts`:

```bash
php artisan vendor:publish --tag=social-meta:assets
```

> Não esqueça de ajustar os paths das fontes em `config/social-meta.php`.

## Uso

Dentro do seu `master.blade.php` (obrigado [Blade UI Kit](https://blade-ui-kit.com/docs/0.x/social-meta)):

```blade
<x-social-meta
    title="{{ $__env->yieldContent('title', 'A cultura come a estratégia no café da manhã.') }} | {{ config('app.name') }}"
    description="{{ $__env->yieldContent('description') }}"
/>
```

Nas views filhas:

```blade
@extends('agenciafmd/frontend::master')

@section('title', 'A cultura come a estratégia no café da manhã.')
@section('description', 'Esta é uma frase de Peter Drucker, considerado o pai da administração moderna.')
```

### Atributos do componente

| Atributo | Padrão | Descrição |
|---|---|---|
| `title` | — (obrigatório) | Usado no `<title>` e no `og:title` |
| `description` | `''` | `description` e `og:description` (omitidos quando vazio) |
| `type` | `website` | `og:type` |
| `card` | `summary_large_image` | `twitter:card` |
| `image` | `''` | `og:image`; quando vazio, a imagem é gerada automaticamente |
| `url` | `url()->current()` | `og:url` e texto impresso na imagem gerada |
| `author` | `Agência F&MD` | `author` |

### Saída

```html
<title>A cultura come a estratégia no café da manhã | Laravel</title>

<meta name="twitter:card" content="summary_large_image" />

<meta property="og:type" content="website" />
<meta property="og:title" content="A cultura come a estratégia no café da manhã | Laravel" />

<meta name="description" content="Esta é uma frase de Peter Drucker, considerado o pai da administração moderna." />
<meta property="og:description" content="Esta é uma frase de Peter Drucker, considerado o pai da administração moderna." />

<meta property="og:image" content="http://starternovo.local/storage/open-graph/facebook/a-cultura-come-a-estrategia-no-cafe-da-manha.png" />
<meta property="og:url" content="http://starternovo.local" />
<meta property="og:locale" content="pt_BR" />
<meta property="og:site_name" content="Laravel" />
<meta name="author" content="Agência F&MD" />
```

### Imagem Open Graph

![OpenGraph Image](docs/screenshot.jpg "OpenGraph Image")

A imagem é gerada com o título (a parte antes do último `|`) e gravada uma única vez em `open-graph/{type}/{slug-do-titulo}.png` no disco padrão. Para gerar uma nova versão, apague o arquivo.

Também é possível usar o serviço diretamente:

```php
use Facades\Agenciafmd\SocialMeta\Services\OpenGraphImage;

$url = OpenGraphImage::generate('Título do artigo', 'https://fmd.ag/blog');
```

### Debug

Coloque no `routes/web.php` e acesse `/debug` para visualizar o card sem gravá-lo:

```php
use Facades\Agenciafmd\SocialMeta\Services\OpenGraphImage;

Route::get('/debug', function () {
    return OpenGraphImage::render();
});
```

## Testes

Os testes ficam em `tests/` e usam o `Tests\TestCase` da aplicação, então são executados de dentro do projeto que instala o pacote:

```bash
vendor/bin/pest packages/agenciafmd/laravel-social-meta/tests
```

## Licença

Este pacote é software livre e está disponível nos termos da licença MIT.
