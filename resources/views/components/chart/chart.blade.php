@props(['type', 'name', 'data', 'orientation' => 'x', 'colors'])

<div class="w-100">
    <canvas style=" max-width: 100%;" id="{{ $name }}-chart" {{ $attributes }}></canvas>
</div>

{{-- Default colors and animations --}}
@php
    $defaultBgColors = [
        'rgba(33, 110, 78, 0.5)',
        'rgba(127, 95, 1, 0.5)',
        'rgba(158, 76, 0, 0.5)',
        'rgba(174, 46, 36, 0.5)',
        'rgba(128, 63, 165, 0.5)',
        'rgba(21, 88, 188, 0.5)',
        'rgba(32, 106, 131, 0.5)',
        'rgba(76, 107, 31, 0.5)',
        'rgba(148, 61, 115, 0.5)',
        'rgba(99, 102, 107, 0.5)',

        'rgba(75, 206, 151, 0.5)',
        'rgba(221, 179, 14, 0.5)',
        'rgba(252, 167, 0, 0.5)',
        'rgba(248, 113, 104, 0.5)',
        'rgba(201, 124, 244, 0.5)',
        'rgba(102, 157, 241, 0.5)',
        'rgba(108, 195, 224, 0.5)',
        'rgba(148, 199, 72, 0.5)',
        'rgba(231, 116, 187, 0.5)',
        'rgba(150, 153, 158, 0.5)',

        'rgba(22, 75, 53, 0.5)',
        'rgba(83, 63, 4, 0.5)',
        'rgba(105, 50, 0, 0.5)',
        'rgba(93, 31, 26, 0.5)',
        'rgba(72, 36, 93, 0.5)',
        'rgba(18, 50, 99, 0.5)',
        'rgba(22, 69, 85, 0.5)',
        'rgba(55, 71, 31, 0.5)',
        'rgba(80, 37, 63, 0.5)',
        'rgba(75, 77, 81, 0.5)',
    ];

    $defaultBdColors = [
        'rgb(33, 110, 78)',
        'rgb(127, 95, 1)',
        'rgb(158, 76, 0)',
        'rgb(174, 46, 36)',
        'rgb(128, 63, 165)',
        'rgb(21, 88, 188)',
        'rgb(32, 106, 131)',
        'rgb(76, 107, 31)',
        'rgb(148, 61, 115)',
        'rgb(99, 102, 107)',

        'rgb(75, 206, 151)',
        'rgb(221, 179, 14)',
        'rgb(252, 167, 0)',
        'rgb(248, 113, 104)',
        'rgb(201, 124, 244)',
        'rgb(102, 157, 241)',
        'rgb(108, 195, 224)',
        'rgb(148, 199, 72)',
        'rgb(231, 116, 187)',
        'rgb(150, 153, 158)',

        'rgb(22, 75, 53)',
        'rgb(83, 63, 4)',
        'rgb(105, 50, 0)',
        'rgb(93, 31, 26)',
        'rgb(72, 36, 93)',
        'rgb(18, 50, 99)',
        'rgb(22, 69, 85)',
        'rgb(55, 71, 31)',
        'rgb(80, 37, 63)',
        'rgb(75, 77, 81)',
    ];

    $bgColors = isset($colors) ? array_map(fn($index) => $defaultBgColors[$index], $colors) : $defaultBgColors;
    $bdColors = isset($colors) ? array_map(fn($index) => $defaultBdColors[$index], $colors) : $defaultBdColors;

    switch ($type) {
        case 'pie':
            $animations = [
                'animateRotate' => [
                    'from' => false,
                    'to' => true,
                ],
            ];
            break;

        case 'bar':
            $animations = [
                'y' => [
                    'from' => 500,
                ],
            ];
            break;
    }
@endphp

<script>
    window["chart-{{ $name }}"] = document.getElementById("{{ $name }}-chart").getContext('2d');

    window["nChart-{{ $name }}"] = new Chart(window["chart-{{ $name }}"], {
        type: "{{ $type }}",
        data: {
            labels: @json(array_keys($data)),
            datasets: [{
                label: "{{ $name }}",
                data: @json(array_values($data)),
                backgroundColor: @json($bgColors),
                borderColor: @json($bdColors),
                hoverOffset: 4,
                borderWidth: 2
            }]
        },
        options: {
            animation: {
                duration: 1000,
                delay: 100,
                easing: 'easeInOutQuart',
            },
            animations: @json($animations),
            indexAxis: "{{ $orientation }}",
        }
    });
</script>
