<?php

function toast_push(string $type, string $message): void
{
    $toasts = session('toasts', []);

    $toasts[] = compact('type', 'message');

    session()->put('toasts', $toasts);
}

function toast(): object
{
    return new class {
        public function success(string $message): void
        {
            toast_push('success', $message);
        }

        public function warning(string $message): void
        {
            toast_push('warning', $message);
        }

        public function danger(string $message): void
        {
            toast_push('danger', $message);
        }

        public function info(string $message): void
        {
            toast_push('info', $message);
        }
    };
}
