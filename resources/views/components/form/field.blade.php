@props([
    'id',
    'label' => str_replace('_', ' ', ucwords($id)),
    'type' => 'text',
    'placeholder' => null,
    'old' => $id,
    'value' => null,
    'options' => null,
    'last' => null
])

@php
    if ($old == false) {
        $value = '';
    } elseif ($old == $id && $value == null) {
        $value = old($old);
    }
@endphp


<div class="form-floating @if (!$last)mb-3 @endif">
    @if ($type == 'textarea')
        {{-- Textarea --}}
        <textarea id="{{ $id }}" name="{{ $id }}" class="form-control @error($id) is-invalid @enderror"
            style="height: 150px;" placeholder="{{ $placeholder }}" cols="30" rows="7" {{ $attributes }}>{{ $value }}</textarea>

    @elseif ($type == 'select')
        {{-- Select --}}
        <select class="form-select" name="{{ $id }}" id="{{ $id }}" aria-label="{{ $label }}"
            {{ $attributes }}>
            @foreach ($options as $option)
                <option value="{{ $option }}" {{ $value == $option || $loop->first ? 'selected' : '' }}>
                    {{ ucwords($option) }}
                </option>
            @endforeach
        </select>

        <label for="{{ $id }}">{{ $label }}</label>

    @elseif(str_contains($id, 'password'))
        {{-- Password --}}
        <div class="input-group mb-3">
            <div class="form-floating">
                <input id="{{ $id }}" type="{{ $type }}"
                    class="form-control @error($id) is-invalid @enderror" name="{{ $id }}"
                    placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
                <label for="{{ $id }}">{{ $label }}</label>
            </div>
            <span id='{{ $id }}-btn' class="input-group-text"><i id="{{ $id }}-icon"
                    class="bi bi-eye-slash-fill btn"></i></span>
        </div>

    @else
        {{-- Text input --}}
        <input id="{{ $id }}" type="{{ $type }}"
            class="form-control @error($id) is-invalid @enderror" name="{{ $id }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    {{-- Error Message --}}
    @error($id)
        <p class="text-danger-emphasis m-1 ">{{ $message }}</p>
    @enderror
</div>

@if (str_contains($id, 'password'))
    <script>
        {
            const input = document.getElementById("{{ $id }}");
            const btn = document.getElementById(`${input.id}-btn`);
            const icon = document.getElementById(`${input.id}-icon`);

            btn.addEventListener("mousedown", () => {
                input.type = "text";
                icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
            });

            btn.addEventListener("mouseup", () => {
                input.type = "password";
                icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            });

            btn.addEventListener("mouseleave", () => {
                input.type = "password";
                icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            });
        }
    </script>
@endif
