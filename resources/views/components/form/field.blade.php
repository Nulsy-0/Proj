@props([
    'id',
    'label' => ucwords($id),
    'type' => 'text',
    'placeholder' => null,
    'label',
    'old' => $id,
    'value' => null,
    'options' => null
])

@php
    if($old == 'false'){
        $value = "";
    }else if($old == $id){
        $value = old($old);
    }
@endphp

<div class="form-floating mb-3">
    {{-- Textarea --}}
    @if ($type == 'textarea')
        <textarea id="{{ $id }}" name="{{ $id }}" class="form-control @error($id) is-invalid @enderror" style="height: 150px;" placeholder="{{ $placeholder }}" cols="30" rows="7" {{ $attributes }}>{{ $value }}</textarea>
    
    {{-- Select --}}
    @elseif ($type == 'options')
        <select class="form-select" name="{{ $id }}" id="{{ $id }}" aria-label="{{ $label }}" {{ $attributes }}>
            @foreach ($options as $option)
                <option 
                    value="{{ $option }}"
                    {{ $loop->first ? 'selected' : '' }}
                >
                    {{ ucwords($option) }}
                </option>
            @endforeach
        </select>
        
        <label for="{{ $id }}">{{ $label }}</label>

    {{-- Password --}}
    @elseif(str_contains($id, 'password'))
        <div class="input-group mb-3">
            <div class="form-floating">
                <input id="{{ $id }}" type="{{ $type }}" class="form-control @error($id) is-invalid @enderror" name="{{ $id }}" placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
                <label for="{{ $id }}">{{ $label }}</label>
            </div>
            <span class="input-group-text"><i class="bi bi-eye-slash-fill btn"></i></span>
        </div>
    
    {{-- Text input --}}
    @else
        <input id="{{ $id }}" type="{{ $type }}" class="form-control @error($id) is-invalid @enderror" name="{{ $id }}" placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    {{-- Error Message --}}
    @error($id)
        <p class="text-danger-emphasis m-1 ">{{ $message }}</p>
    @enderror
</div>

<script>
    const id = "{{ $id }}";
    const hideBtn = document.querySelector(`#${id}`);

    hideBtn.on('mousedown mouseup', function mouseState(e) {
        if (e.type == "mousedown") {
            hideBtn.type = "text";
        }
    });
</script>