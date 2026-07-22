@props(['list', 'labelsChart', 'percent', 'pieData', 'stats'])

<x-layout>
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class=" d-flex align-items-center gap-3">
                <h2 class="mb-0">{{ $list->name }}</h2>
            </div>
        </div>
    </div>

    <div class="row mt-5 gap-5">
        {{-- Top left square --}}
        <div class="col-md bg-secondary-subtle border p-3 rounded-4 shadow-lg border-secondary">
            <h4 class="mb-3 text-muted">Labels</h4>

            <x-chart :colors="$labelColors" type="bar" name="Label Quantity" :data="$labelsChart" />
        </div>


        {{-- Top right square --}}
        <div class="col-md bg-secondary-subtle border p-3 rounded-4 shadow-lg border-secondary">
            <div class="row gap-3">
                {{-- List --}}
                <div class="col">
                    <h4 class="mb-3 text-muted">Distribution by user</h4>

                    <div class="d-flex flex-column gap-2">
                        @foreach ($percent as $user)
                            <div
                                class="d-flex justify-content-between align-items-center p-2 rounded-3 border border-secondary">
                                <div class="small">
                                    <span class="text-muted"><strong>{{ $user['sN'] }}</strong> |—>
                                        {{ $user['fN'] }}</span>
                                </div>

                                <span class="badge bg-info rounded-pill text-dark">
                                    {{ $user['count'] . ' | ' . $user['percent'] }}%
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Chart --}}
                <div class="col text-center">
                    <h5 class=" text-muted">Graph</h5>

                    <div class="p-3">
                        <x-chart type="pie" name="Made" :data="$pieData" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Statistics --}}
    @if (isset($stats))
        <div
            class="mt-5 bg-secondary-subtle border text p-4 rounded-4 shadow-lg border-secondary row gap-3 d-flex align-content-center">
            <h4 class="mb-1 text-muted">Statistics</h4>

            @foreach ($stats as $key => $statGroup)
                <div class="btn-div col-md justify-content-center">
                    <div class="col-md">
                        <h5 class="text-muted">{{ $statGroup['name'] }}</h5>

                        <div class="list-group rounded-3">
                            @switch($statGroup['type'])
                                @case('labels')
                                    @foreach ($statGroup['fields'] as $field)
                                        <div class="list-group-item d-flex p-0">
                                            <div class="flex-grow-1 p-3 col align-content-center">{{ $field['name'] }}</div>
                                            <div class="border-start p-3 fw-bold col text-center align-content-center ">
                                                {{ $field['data'] }}</div>
                                        </div>
                                    @endforeach
                                @break

                                @case('dates')
                                    @foreach ($statGroup['fields'] as $field)
                                        <div class="list-group-item d-flex p-0">
                                            <div class="flex-grow-1 p-3 col align-content-center">{{ $field['name'] }}</div>
                                            <div class="border-start p-3 fw-bold col text-center align-content-center">
                                                {{ date('d/m/Y', strtotime($field['data'])) }}</div>
                                        </div>
                                    @endforeach
                                @break

                                @case('extras')
                                    @foreach ($statGroup['fields'] as $field)
                                        <div class="list-group-item d-flex p-0">
                                            <div class="flex-grow-1 p-3 col align-content-center">{{ $field['name'] }}</div>
                                            <div class="border-start p-3 fw-bold col text-center align-content-center">
                                                {{-- This @if checks for "01/01/1970", which is the value returned when the input string is not a valid date --}}
                                                @if (date('d/m/Y', strtotime($field['data'])) == '01/01/1970')
                                                    {{ $field['data'] }}
                                                @else
                                                    {{ date('d/m/Y', strtotime($field['data'])) }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @break
                            @endswitch
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>
