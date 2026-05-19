<!DOCTYPE html>
<html lang="pt">

@props([
    'title' => 'Media Organizer'
])

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/webp" href="https://mediaprisma.pt/wp-content/uploads/elementor/thumbs/3-qyu79tyipayzrbuvyxh65x94rseyq53yi02aim083c.webp"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ $title }}</title>
</head>

@php
    $theme = $_COOKIE['theme'];
@endphp

<body data-bs-theme="{{ $theme }}">
    <header>
        <x-layout.nav :theme="$theme" />
    </header>

    <main class="container-fluid">
        {{ $slot }}
    </main>

    <x-layout.nutification />

    <footer>
        <x-layout.footer />
    </footer>
</body>

</html>