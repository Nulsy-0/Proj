@props([
    'obj',
    'fields',
    'id',
])

<input class="form-control mb-3" id="{{ $id }}-search" type="text" placeholder="Search..">

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="table-secondary">
                @foreach ($fields as $field)
                    <th scope="col">
                        {{ is_array($field) ? $field[0] : $field }}
                    </th>
                @endforeach
                <th></th>
            </tr>
        </thead>

        <tbody id="{{ $id }}-table">
            @foreach ($obj as $item)
                <tr>
                    @foreach ($fields as $key => $field)

                        @php
                            $isArray = is_array($field);
                            $column = $isArray ? $field[0] : $field;
                            $value = data_get($item, $column);
                        @endphp

                        {{-- Header --}}
                        @if ($key === 0)
                            <th scope="row">
                                {{ $value }}
                            </th>
                            
                        {{-- created_at format --}}
                        @elseif ($column === 'created_at' && $value)
                            <td>
                                {{ $value->format('D, d M Y') }},
                                {{ $value->diffForHumans() }}
                            </td>

                        {{-- arrays --}}
                        @elseif ($isArray)
                            <td>
                                @if (empty($value) || (is_countable($value) && count($value) === 0))
                                    <span class="badge text-bg-secondary">
                                        No {{ $column }}
                                    </span>
                                @else
                                    @foreach ($value as $var)
                                        <span class="badge text-bg-secondary">
                                            {{ is_object($var) ? ($var->name ?? $var->id) : $var }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>

                        {{-- default --}}
                        @else
                            <td>{{ $value }}</td>
                        @endif

                    @endforeach
                    
                    <td><a class="btn" href="{{ route($id.'.edit', [$item->id]) }}"><i class="bi bi-pencil-square"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- search script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const id = "{{ $id }}"
        const input = document.getElementById(`${id}-search`);
        const rows = document.querySelectorAll(`#${id}-table tr`);

        input.addEventListener("keyup", function () {
            const value = this.value.toLowerCase();

            rows.forEach(row => {
                row.style.display =
                    row.textContent.toLowerCase().includes(value)
                        ? ""
                        : "none";
            });
        });

    });
</script>