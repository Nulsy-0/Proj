@props(['divPos', 'labels', 'stats' => null, 'count' => 0])
{{-- @dd($stats) --}}

@php
    $mode = $stats[$divPos]['type'] ?? 'labels';
    $types = ['labels', 'dates', 'extras'];
@endphp

<div class="bg-body w-100 h-100 rounded-3 p-3">
    <x-form.field id="stats[{{ $divPos }}][name]" label="Stat Group Name" :value="$stats[$divPos]['name'] ?? ''" required />

    <h6 class="text-muted" @popper(By selecting another type the stat group will reset)>
        Stat Group Type
        <i class="bi bi-exclamation-circle-fill" style="font-size: small" data-bs-toggle="tooltip"></i> :
    </h6>
    <div class="btn-group w-100 mb-3" role="group">
        @foreach ($types as $type)
            <input type="radio" class="btn-check" name="stats[{{ $divPos }}][type]"
                id="{{ $divPos }}-btn-{{ $type }}" autocomplete="off" value="{{ $type }}"
                @if ($type == $mode) checked @endif>
            <label class="btn btn-outline-secondary"
                for="{{ $divPos }}-btn-{{ $type }}">{{ ucwords($type) }}</label>
        @endforeach
    </div>

    <div id="{{ $divPos }}-mode" class="w-100 d-flex mb-3 gap-3">
        <button type="button" class="btn btn-info btn-sm w-50" value="value">Value</button>
        <button type="button" class="btn btn-info btn-sm w-50" value="calculation">Calculation</button>
    </div>

    <div id="{{ $divPos }}-inputs">
        @if ($stats)
            @foreach ($stats[$divPos]['fields'] as $field)
                <x-admin.stat-field :count="$count" :divPos="$divPos" :field="$field" :labels="$labels"
                    :mode="$mode" />
                @php
                    $count++;
                @endphp
            @endforeach
        @endif
    </div>

    {{-- Delete modal --}}
    <button id="{{ $divPos }}-delete-btn" type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
        data-bs-target="#{{ $divPos }}-delete-stat-group-modal">
        Delete
        <i class="bi bi-trash3-fill"></i>
    </button>

    <x-layout.modal id="{{ $divPos }}-delete-stat-group" :btn="false" head="Delete Stat Group"
        :delete="true">
        <p>
            By pressing "Delete" this Stat Group ( <span id="{{ $divPos }}-span"
                class="text-decoration-underline"></span> )
            will no longer exists!
        </p>
        <b>Are o shore that you want to delete it?</b>
    </x-layout.modal>

    <script>
        var count_{{ $divPos }} = {{ $count }} ?? 0;
        mode_{{ $divPos }} = document.getElementById("{{ $divPos }}-mode");
        selection_{{ $divPos }} = document.querySelectorAll("input[name='stats[{{ $divPos }}][type]']");

        selection_{{ $divPos }}.forEach(selected => {
            selected.addEventListener('click', () => {
                count_{{ $divPos }} = 0;
                const inputs_{{ $divPos }} = document.getElementById("{{ $divPos }}-inputs");

                inputs_{{ $divPos }}.innerHTML = null;
            });
        });

        mode_{{ $divPos }}.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const inputs_{{ $divPos }} = document.getElementById("{{ $divPos }}-inputs");
                const selected_{{ $divPos }} = document.querySelector(
                    "input[name='stats[{{ $divPos }}][type]']:checked"
                );

                {{--
                    needs some work
                    const calculation = document.querySelectorAll(
                        `[id^="stats[{{ $divPos }}][fields]["][id$="[name]"][data-type="calculation"]`
                    );
                    var labels = [];
                    calculation.forEach(input => {
                        labels.push(input.value)
                    });
                --}}

                const inputsViews = {
                    labels: {
                        value: `<x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][name]" label="Name" :delete="true" data-type="value" required>
                            <input type="text" readonly hidden value="${btn.value}" name="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][type]" />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Label" type="select"
                                :options="$labels" required />
                        </x-form.field>`,

                        calculation: `<x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][name]" label="Name" :delete="true" data-type="calculation" required>
                            <input type="text" readonly hidden value="${btn.value}" name="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][type]" />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Label" type="select" :options="$labels" required />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Calculation" type="select"
                                :options="['+', '-', '×', '÷']" required />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Label" type="select" :options="$labels" required />
                        </x-form.field>`
                    },

                    dates: {
                        value: `<x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][name]" label="Name" :delete="true" data-type="value" required>
                            <input type="text" readonly hidden value="${btn.value}" name="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][type]" />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Date" type="date" required />
                        </x-form.field>`,

                        calculation: `<x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][name]" label="Name" :delete="true" data-type="calculation" required>
                            <input type="text" readonly hidden value="${btn.value}" name="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][type]" />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Date" type="select" :options="['Start Date']" required />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Calculation" type="select"
                                :options="['+', '-']" required />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Label" type="select"
                                :options="$labels" required />
                        </x-form.field>`
                    },

                    extras: {
                        value: `<x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][name]" label="Name" :delete="true" data-type="value" required>
                            <input type="text" readonly hidden value="${btn.value}" name="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][type]" />
                            <x-form.field id="stats[{{ $divPos }}][fields][${count_{{ $divPos }}}][data][]" :div="false" label="Extra" type="select"
                                :options="['Board Name', 'Start Date']" required />
                        </x-form.field>`
                    }
                };

                const html = selected_{{ $divPos }}.value == "extras" ?
                    inputsViews[selected_{{ $divPos }}.value]["value"] :
                    inputsViews[selected_{{ $divPos }}.value][btn.value];
                // Because extra doesn't have calculation
                if (html) {
                    inputs_{{ $divPos }}.insertAdjacentHTML('beforeend', html);
                    count_{{ $divPos }}++;
                }
            });
        });

        // Modal span text
        deleteBtn_{{ $divPos }} = document.getElementById("{{ $divPos }}-delete-btn");
        deleteBtn_{{ $divPos }}.addEventListener('click', () => {
            const span_{{ $divPos }} = document.getElementById("{{ $divPos }}-span");
            const name_{{ $divPos }} = document.getElementById("stats[{{ $divPos }}][name]");

            span_{{ $divPos }}.innerText = name_{{ $divPos }}.value;
        });

        // Delete stat group and create new "plus-btn"
        modalDeleteBtn_{{ $divPos }} = document.getElementById("{{ $divPos }}-delete-stat-group-btn");
        modalDeleteBtn_{{ $divPos }}.addEventListener('click', () => {
            parent_{{ $divPos }} = document.getElementById("{{ $divPos }}");
            parent_{{ $divPos }}.innerHTML = `
                <div class="plus-btn">
                    <button type="button" class="btn btn-info">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            `;
        });

        // undeclare the variables
        delete mode_{{ $divPos }};
        delete deleteBtn_{{ $divPos }};
        delete modalDeleteBtn_{{ $divPos }};
        delete selection_{{ $divPos }};
    </script>

    {{-- Because this component is injected thro js --}}
    @include('popper::assets')
</div>


{{-- 

    stat view
    └── Give a name for that "stat group"
        ├── list->labels
        │   ├── calculation between 2 labels and name for that
        │   └── value of a label and name for that
        ├── dates
        │   ├── start_date
        │   └── calculation between start_date and some label
        └── extras
            ├── start_date
            └── board name

--}}
