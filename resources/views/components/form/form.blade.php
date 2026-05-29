@props([
    'action',
    'method',
    'title' => null,
    'subtitle' => null,
    'id'
])
@php
    if ($method == 'POST' || $method == 'GET') {
        $formMethod = $method;
    } else {
        $formMethod = 'POST';
    }
@endphp

<form @if ($title)
    class="bg-secondary-subtle p-4 rounded-5 shadow-lg col-10 col-md-6 col-lg-4"
@else
    id="{{ $id }}-form"
@endif action="{{ $action }}" method="{{ $formMethod }}" {{ $attributes }}>
    @csrf
    @if ($method != 'POST' && $method != 'GET')
        @method($method)
    @endif

    @if ($title)    
        <div class="text-center">
            <h1 class="fw-bold">{{ $title }}</h1>
            <p class="text-muted mt-1">{{ $subtitle }}</p>
        </div>
    @endif

    {{ $slot }}
    
    @if ($title)    
        <button class="btn btn-info w-100 mt-3" type="submit">
            <i class="bi bi-check"></i> {{ $title }}
        </button> 
    @endif
</form>