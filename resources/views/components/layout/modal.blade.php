@props([
    'pos' => 'end',
    'id',
    'formId',
    'btn' => true,
    'btnText',
    'head',
    'delete' => false,
    'yesBtn' => 'Save',
    'yesBtnIcon' => 'bi-cloud-plus-fill',
    'yesBtnColor' => 'info'
])

@if ($btn)
    <div class="d-flex justify-content-{{ $pos }} mb-3">
        <button id="{{ $id }}-modal-btn" type="button" class="btn btn-info" data-bs-toggle="modal"
            data-bs-target="#{{ $id }}-modal">
            <i class="bi bi-plus-lg"></i>
            {{ $btnText }}
        </button>
    </div>
@endif

<div class="modal fade" id="{{ $id }}-modal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $head }}
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-start">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                    <i class="bi bi-x-lg"></i>
                </button>

                @if ($delete)
                    <button @if(isset($formId)) type="submit" form="{{ $formId }}-form" @else id="{{ $id }}-btn" type="button" data-bs-dismiss="modal" @endif class="btn btn-danger">
                        Delete
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                @else
                    <button form="{{ $formId }}-form" type="submit" class="btn btn-{{ $yesBtnColor }}">
                        {{ $yesBtn }}
                        <i class="bi {{ $yesBtnIcon }}"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
