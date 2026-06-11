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

            <x-chart type="bar" name="Label Quantity" :data="$labelsChart" />
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
                                    <span class="text-muted"><strong>{{ $user['sN'] }}</strong> |—> {{ $user['fN'] }}</span>
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
    <div
        class="mt-5 bg-secondary-subtle border text p-4 rounded-4 shadow-lg border-secondary row gap-3 d-flex align-content-center">
        <h4 class="mb-1 text-muted">Statistics</h4>

        <div class="col-md">
            <h5 class="text-muted">Approved / Delivered:</h5>

            <div class="list-group rounded-3">
                <div class="list-group-item d-flex p-0">
                    <div class="flex-grow-1 p-3 col-10">Approved Posts</div>
                    <div class="border-start p-3 fw-bold col-2 text-center">{{ $approved }}</div>
                </div>

                <div class="list-group-item d-flex p-0">
                    <div class="flex-grow-1 p-3 col-10">Not Approved Posts</div>
                    <div class="border-start p-3 fw-bold col-2 text-center">{{ $notApproved }}</div>
                </div>

                <div class="list-group-item d-flex p-0">
                    <div class="flex-grow-1 p-3 col-10">Delivered Posts</div>
                    <div class="border-start p-3 fw-bold col-2 text-center">{{ $delivered }}</div>
                </div>

                <div class="list-group-item d-flex p-0">
                    <div class="flex-grow-1 p-3 col-10">Not Delivered Posts</div>
                    <div class="border-start p-3 fw-bold col-2 text-center">{{ $notDelivered }}</div>
                </div>
            </div>
        </div>

        <div class="col-md">
            <h5 class="text-muted">Predictions:</h5>

            @php
                $a = 0;
                $i = 0;
                $test = '';

                if ($approved != 0) {
                    while ($i < $approved) {
                        $temp = date('Y-m-d', strtotime($list->start_date . " +{$a} day"));

                        if (in_array(\Carbon\Carbon::parse($temp)->translatedFormat('D'), $list->days)) {
                            $i++;
                            $test = $temp;
                        }

                        $a++;
                    }
                } else {
                    $test = $list->start_date;
                }

                $rest = $delivered + $notDelivered;
                $all = date('Y-m-d', strtotime($test . " +{$rest} day"));

            @endphp

            <x-form.field type="date" id="Only Approved:" :value="$test" readonly />
            <x-form.field type="date" id="Over All (Except Not Approved):" :value="$all" readonly />
        </div>

        <div class="col-md">
            <h5 class="text-muted">Info of the List:</h5>
            
            <x-form.field id="On Board:" :value="$trelloBoard->name" readonly />
            <x-form.field type="date" id="Date Of Start:" :value="$list->start_date" readonly />
            
        </div>
    </div>
</x-layout>
