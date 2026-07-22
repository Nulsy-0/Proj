{{-- blade-formatter-disable-next-line --}}
@props([
'id',
'label' => ucwords(str_replace('_',' ', $id)),
'type' => 'text',
'options' => null, // In case of type == select example -> ['test1', 'test2', 'test3']
'placeholder' => null,
'old' => $id,
'value' => null,
'class' => null,
'last' => false, // No "mb-3"
'delete' => false, // For a field that can be removed
'div' => true, // Only change if is in "multiple field"
'parentId' => null // Must be the id of the main field if $div == false
])

@php
    if ($old == false) {
        $value = '';
    } elseif ($old == $id && $value == null) {
        $value = old($old);
    }
@endphp

@if ($div)
    <div id="{{ $id }}-input-div" class="input-group {{ $last ?: 'mb-3' }}">
@endif

<div class="form-floating">
    @if ($type == 'textarea')

        {{-- Textarea --}}
        <textarea id="{{ $id }}" name="{{ $id }}"
            class="form-control {{ $class }} @error($id) is-invalid @enderror" style="height: 150px;"
            placeholder="{{ $placeholder }}" cols="30" rows="7" {{ $attributes }}>{{ $value }}</textarea>
    @elseif ($type == 'select')
        {{-- Select --}}
        <select class="form-select" name="{{ $id }}" id="{{ $id }}"
            aria-label="{{ $label }}" {{ $attributes }}>
            @foreach ($options as $option)
                <option value="{{ $option }}" {{ $value == $option || $loop->first ? 'selected' : '' }}>
                    {{ ucwords($option) }}
                </option>
            @endforeach
        </select>
    @elseif(str_contains($id, 'password'))
        {{-- Password --}}
        <input id="{{ $id }}" type="{{ $type }}"
            class="form-control {{ $class }} @error($id) is-invalid @enderror" name="{{ $id }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
    @else
        {{-- Default/date input --}}
        <input id="{{ $id }}" type="{{ $type }}"
            class="form-control {{ $class }} @error($id) is-invalid @enderror" name="{{ $id }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>
    @endif

    @if ($type == 'date' || $type == 'datetime')
        <label for="{{ $id }}">
            <i class="bi bi-calendar-event"></i>
            {{ $label }}
        </label>
    @else
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
</div>

{{-- * slot in case of "multiple field" --}}
{{ $slot }}

@if ($div)
    @if (str_contains($id, 'password'))
        <span id='{{ $id }}-eye-btn' class="rounded-end-2 d-flex align-content-center input-group-text"><i
                id="{{ $id }}-eye-icon" class="bi bi-eye-slash-fill btn"></i></span>
    @elseif ($delete == true)
        <span id='{{ $id }}-delete-btn'
            class="d-flex align-content-center input-group-text btn btn-danger rounded-end-2"
            onclick="document.getElementById('{{ $id }}-input-div').remove()">
            <i class="bi bi-trash3-fill"></i>
        </span>
    @endif

    @if (str_contains($id, 'password'))
        {{-- Not ideal but is the best i can --}}
        <script>
            btn_{{ $id }} = document.getElementById("{{ $id }}-eye-btn");

            btn_{{ $id }}.addEventListener("mousedown", () => {
                field_{{ $id }} = document.getElementById("{{ $id }}");
                icon_{{ $id }} = document.getElementById("{{ $id }}-eye-icon");

                field_{{ $id }}.type = "text";
                icon_{{ $id }}.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
            });

            btn_{{ $id }}.addEventListener("mouseup", () => {
                field_{{ $id }} = document.getElementById("{{ $id }}");
                icon_{{ $id }} = document.getElementById("{{ $id }}-eye-icon");

                field_{{ $id }}.type = "password";
                icon_{{ $id }}.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            });

            btn_{{ $id }}.addEventListener("mouseleave", () => {
                field_{{ $id }} = document.getElementById("{{ $id }}");
                icon_{{ $id }} = document.getElementById("{{ $id }}-eye-icon");


                field_{{ $id }}.type = "password";
                icon_{{ $id }}.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            });

            delete btn_{{ $id }}
        </script>
    @endif
    </div>
@endif

{{-- Error Message --}}
@error(preg_replace(['/\\[/', '/\\]/'], ['.', ''], $id))
    @if ($parentId)
        <script>
            parent_{{ $parentId }} = document.getElementById("{{ $parentId }}-inputs");
            error_{{ $id }} = `<p class = "text-danger-emphasis m-1 "> {{ $message }} </p>`
            parent_{{ $parentId }}.insertAdjacentHTML('afterend', error_{{ $id }});

            delete parent_{{ $parentId }};
            delete error_{{ $id }};
        </script>
    @else
        <p class = "text-danger-emphasis m-1 mt-n3"> {{ $message }}</p>
    @endif
@enderror
