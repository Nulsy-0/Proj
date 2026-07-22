@props(['count', 'divPos', 'field', 'labels', 'mode'])

@php
    $baseId = "stats[$divPos][fields][$count]";
    $data = $field['data'] ?? [];
@endphp

@switch ($mode)
    @case('labels')
        @if ($field['type'] === 'calculation')
            <x-form.field id="{{ $baseId }}[name]" label="Name" :delete="true" :value="$field['name'] ?? null" data-type="{{ $field['type'] }}" required>
                <input type="hidden" value="{{ $field['type'] }}" name="{{ $baseId }}[type]" />

                <x-form.field id="{{ $baseId }}[data][0]" :div="false" label="Label" type="select" :options="$labels"
                    :value="$data[0] ?? null" parentId="{{ $divPos }}" required />

                <x-form.field id="{{ $baseId }}[data][1]" :div="false" label="Calculation" type="select"
                    :options="['+', '-', '×', '÷']" :value="$data[1] ?? null" parentId="{{ $divPos }}" required />

                <x-form.field id="{{ $baseId }}[data][2]" :div="false" label="Label" type="select"
                    :options="$labels" :value="$data[2] ?? null" parentId="{{ $divPos }}" required />
            </x-form.field>
        @else
            <x-form.field id="{{ $baseId }}[name]" label="Name" :delete="true" :value="$field['name'] ?? null" data-type="{{ $field['type'] }}" required>
                <input type="hidden" value="{{ $field['type'] }}" name="{{ $baseId }}[type]" />

                <x-form.field id="{{ $baseId }}[data][0]" :div="false" label="Label" type="select"
                    :options="$labels" :value="$data[0] ?? null" required />
            </x-form.field>
        @endif
    @break

    @case('dates')
        @if ($field['type'] === 'calculation')
            <x-form.field id="{{ $baseId }}[name]" label="Name" :delete="true" :value="$field['name'] ?? null" data-type="{{ $field['type'] }}" required>
                <input type="hidden" value="{{ $field['type'] }}" name="{{ $baseId }}[type]" />

                <x-form.field id="{{ $baseId }}[data][0]" :div="false" label="Date" type="select"
                    :options="['Start Date']" :value="$data[0] ?? null" required />

                <x-form.field id="{{ $baseId }}[data][1]" :div="false" label="Calculation" type="select"
                    :options="['+', '-']" :value="$data[1] ?? null" required />

                <x-form.field id="{{ $baseId }}[data][2]" :div="false" label="Label" type="select"
                    :options="$labels" :value="$data[2] ?? null" required />
            </x-form.field>
        @else
            <x-form.field id="{{ $baseId }}[name]" label="Name" :delete="true" :value="$field['name'] ?? null" data-type="{{ $field['type'] }}" required>
                <input type="hidden" value="{{ $field['type'] }}" name="{{ $baseId }}[type]" />

                <x-form.field id="{{ $baseId }}[data][0]" :div="false" label="Date" type="date"
                    :value="$data[0] ?? null" required />
            </x-form.field>
        @endif
    @break

    @default
        <x-form.field id="{{ $baseId }}[name]" label="Name" :delete="true" :value="$field['name'] ?? null" data-type="{{ $field['type'] }}" required>
            <input type="hidden" value="{{ $field['type'] }}" name="{{ $baseId }}[type]" />

            <x-form.field id="{{ $baseId }}[data][0]" :div="false" label="Extra" type="select" :options="['Board Name', 'Start Date']"
                :value="$data[0] ?? null" required />
        </x-form.field>
@endswitch
