@php
    $toasts = session()->pull('toasts', []);

    $icons = [
        'success' => 'bi-check-circle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'danger' => 'bi-x-octagon-fill',
        'info' => 'bi-info-circle-fill',
    ];
@endphp

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastStack">
    @foreach ($toasts as $toast)
        <div class="toast align-items-center text-bg-{{ $toast['type'] }} border-0 mb-2" role="alert"
            data-bs-delay="4000" data-bs-autohide="true">

            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi {{ $icons[$toast['type']] ?? '' }}"></i>
                    <span>{{ $toast['message'] }}</span>
                </div>

                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach((el, i) => {
            const toast = new bootstrap.Toast(el, {
                autohide: false
            });
            let timer;
            const startTimer = () => {
                timer = setTimeout(() => {
                    toast.hide();
                }, 5000);
            };

            const stopTimer = () => {
                clearTimeout(timer);
            };

            setTimeout(() => {
                toast.show();
                startTimer();
            }, i * 150);

            el.addEventListener('mouseenter', stopTimer);
            el.addEventListener('mouseleave', startTimer);

            el.addEventListener('hidden.bs.toast', () => {
                clearTimeout(timer);
            });
        });
    });
</script>
