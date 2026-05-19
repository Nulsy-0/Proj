@php
    $notification = null;
    $message = null;

    if (session('success')) {
        $notification = 'success';
        $message = session('success');
    } elseif (session('warning')) {
        $notification = 'warning';
        $message = session('warning');
    } elseif (session('danger')) {
        $notification = 'danger';
        $message = session('danger');
    }

    $icons = [
        'success' => '<i class="bi bi-check-circle-fill"></i>',
        'warning' => '<i class="bi bi-exclamation-triangle-fill"></i>',
        'danger' => '<i class="bi bi-x-octagon-fill"></i>'
    ];
@endphp

@if ($notification)
    <div id="notification"
        class="fade position-absolute end-0 bottom-0 mb-3 me-3 alert alert-{{ $notification }} d-flex align-items-center"
        role="alert">
        {!! $icons[$notification] !!}
        <div class="ms-2">
            {{ $message }}
        </div>
    </div>
    <script>
        const notification = document.querySelector('#notification');

        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    </script>
@endif