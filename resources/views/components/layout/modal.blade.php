@props([
    'pos' => 'end',
    'add',
    'formId'
])

<div class="d-flex justify-content-{{ $pos }} mb-3">
    <button id="{{ $add }}-modal-btn" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#{{ $add }}-modal">
        <i class="bi bi-plus-lg"></i>
        Add {{ ucfirst($add) }}
    </button>
</div>

<div class="modal fade" id="{{ $add }}-modal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Add {{ ucfirst($add) }}
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button form="{{ $formId }}-form" type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

