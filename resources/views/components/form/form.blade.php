@props([
    'action',
    'method',
    'title',
    'subtitle'
])
@php
    if ($method == 'POST' || $method == 'GET') {
        $formMethod = $method;
    } else {
        $formMethod = 'POST';
    }
@endphp

<div class="d-flex justify-content-center align-items-center min-vh-100">
<form class="bg-secondary-subtle p-4 rounded-5 shadow-lg col-12 col-md-6 col-lg-4" action="{{ $action }}" method="{{ $formMethod }}">
        @csrf
        @if ($method != 'POST' && $method != 'GET')
            @method($method)
        @endif

        <div class="text-center">
        <h1 class="fw-bold">{{ $title }}</h1>
            <p class="text-muted mt-1">{{ $subtitle }}</p>
        </div>
    
        {{ $slot }}
    
        <button class="btn btn-info w-100 mt-3" type="submit">
            <i class="bi bi-check"></i> {{ $title }}
        </button> 
    </form>
</div>