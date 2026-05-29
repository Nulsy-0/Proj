<!DOCTYPE html>
<html lang="en">

@props([
    'title' => 'Media Organizer',
    'logo' => 'https://mediaprisma.pt/wp-content/uploads/elementor/thumbs/3-qyu79tyipayzrbuvyxh65x94rseyq53yi02aim083c.webp',
    'extra' => null
])

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/webp" href="{{ $logo }}"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ $title }}</title>
</head>

@php
    $theme = $_COOKIE['theme'] ?? 'dark';
@endphp

<body data-bs-theme="{{ $theme }}" {{ $attributes }}>
    <header>
        <x-layout.nav :logo="$logo" :theme="$theme" />
    </header>

    <main class="d-flex justify-content-center w-100 min-vh-100 @if ( $extra ){{$extra}}@else{{'mt-5'}}@endif">
        <div class=" w-100 ms-5 me-5">
            {{ $slot }}
        </div>
    </main>

    <x-layout.nutification />

    <footer>
        <x-layout.footer />
    </footer>
</body>

</html>