<title>{{ $title }}</title>

<meta name="twitter:card" content="{{ $card }}"/>

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}"/>

@if ($description)
    <meta name="description" content="{{ $description }}">
    <meta property="og:description" content="{{ $description }}">
@endif

<meta property="og:image" content="{{ asset(OpenGraphImage::generate(Str::of($title)->beforeLast('|'), $url)) }}"/>
<meta property="og:url" content="{{ $url }}"/>
<meta property="og:locale" content="{{ app()->getLocale() }}"/>
<meta property="og:site_name" content="{{ config('app.name') }}"/>
<meta name="author" content="{!! $author !!}">
