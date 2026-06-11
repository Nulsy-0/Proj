@props(['type', 'name', 'data', 'orientation' => 'x', 'color'])

<div class="w-100">
    <canvas style=" max-width: 100%;" id="{{ $name }}-chart" {{ $attributes }}></canvas>
</div>

{{-- Default colors and animations --}}
@php
    $defaultBgColors = [
        'rgba(255, 99, 132, 0.5)',
        'rgba(255, 159, 64, 0.5)',
        'rgba(255, 205, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(201, 203, 207, 0.5)',
        'rgba(40, 167, 69, 0.5)',
        'rgba(220, 53, 69, 0.5)',
        'rgba(255, 20, 147, 0.5)',
        'rgba(0, 206, 209, 0.5)',
        'rgba(255, 140, 0, 0.5)',
        'rgba(106, 90, 205, 0.5)',
        'rgba(50, 205, 50, 0.5)',
        'rgba(128, 0, 128, 0.5)',
        'rgba(255, 69, 0, 0.5)',
        'rgba(0, 128, 128, 0.5)',
        'rgba(184, 134, 11, 0.5)',
        'rgba(70, 130, 180, 0.5)',
        'rgba(123, 104, 238, 0.5)',
    ];
    $defaultBorderColors = [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)',
        'rgb(40, 167, 69)',
        'rgb(220, 53, 69)',
        'rgb(255, 20, 147)',
        'rgb(0, 206, 209)',
        'rgb(255, 140, 0)',
        'rgb(106, 90, 205)',
        'rgb(50, 205, 50)',
        'rgb(128, 0, 128)',
        'rgb(255, 69, 0)',
        'rgb(0, 128, 128)',
        'rgb(184, 134, 11)',
        'rgb(70, 130, 180)',
        'rgb(123, 104, 238)',
    ];

    $bgColors = array_values($color['bg'] ?? $defaultBgColors);
    $bdColors = array_values($color['bd'] ?? $defaultBorderColors);

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
                delay: 1500,
                easing: 'easeInOutQuart',
            },
            animations: @json($animations),
            indexAxis: "{{ $orientation }}",
        }
    });
</script>
