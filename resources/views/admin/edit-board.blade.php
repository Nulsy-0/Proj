@props(['board', 'weeks', 'labels'])

<x-layout>
    {{-- Top area "name, create date, update, delete and delete modal" --}}
    <div class="row align-items-center">
        {{-- Name and Update --}}
        <div class="col-md-6 mb-3">
            <div class=" d-flex align-items-center gap-3">
                <h2 class="mb-0">{{ $board->name }}</h2>

                <button type="submit" form="board-edit-form" class="btn btn-info">Update <i
                        class="bi bi-arrow-repeat"></i></button>
            </div>
        </div>

        {{-- Delete and Created --}}
        <div class="col-md-6 text-md-end">
            <div class="d-flex justify-content-md-end align-items-center gap-3 flex-wrap">

                <p class="mb-0">
                    <strong>Created:</strong>

                    {{ $board->created_at->format('D, d M Y') . ', ' }}

                    <span class="text-decoration-underline">
                        {{ $board->created_at->diffForHumans() }}
                    </span>
                </p>

                <button type="button" data-bs-toggle="modal" data-bs-target="#delete-board-modal"
                    class="btn btn-danger">
                    Delete
                    <i class="bi bi-trash3-fill"></i>
                </button>

                <x-layout.modal id="delete-board" :btn="false" formId="board-delete" head="Delete Board"
                    :delete="true">
                    <x-form id="board-delete" method="DELETE" :action="route('board.delete', ['id' => $board->id])">
                        <input name="id" type="number" value="{{ $board->id }}" hidden readonly>
                    </x-form>
                    <p class="mb-3">
                        By pressing <strong>"Delete"</strong> this Board ( <span
                            class="text-decoration-underline">{{ $board->name }}</span> ) will no longer exists!
                    </p>
                    <p class=" mb-0 fw-bold">Are o shore that you want to delete it?</p>
                </x-layout.modal>
            </div>
        </div>

        <div class="col-md-6 mb-3 text-start">
            <span class="text-decoration-underline text-warning me-1">Refresh Board Lists</span>

            <button type="button" data-bs-toggle="modal" data-bs-target="#reload-board-modal"
                class=" btn-sm btn btn-warning">Refresh <i class="bi bi-three-dots"></i></button>

            <x-layout.modal yesBtn="Refresh" yesBtnIcon="bi-three-dots" yesBtnColor="warning" id="reload-board"
                :btn="false" head="Refresh Board" formId="reload-board-form">

                <p class="mb-3">
                    Clicking <strong>"Refresh"</strong> will synchronize all lists with the current state of the Trello
                    board.
                    This may include updating list names, removing deleted lists, and creating new lists as needed.
                </p>

                <p class="mb-0 fw-bold">
                    Are you sure you want to refresh the lists?
                </p>
                <x-form id="reload-board-form" method="PATCH" :action="route('board.refresh', ['id' => $board->id])" />
            </x-layout.modal>
        </div>
    </div>

    {{-- Main form --}}
    <x-form method="PATCH" id="board-edit" :action="route('board.update', ['id' => $board->id])">
        <div class="row mt-5 gap-5">
            {{-- Enable/Disable Lists --}}
            <div class="col bg-secondary-subtle border border-secondary rounded-4 shadow-lg p-4">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check2-square fs-4 me-2"></i>
                        <h5 class="mb-0 fw-semibold">Active Lists</h5>
                    </div>

                    <span class="badge bg-success">
                        {{ $board->lists->where('state', 'active')->count() }} Active
                    </span>
                </div>

                <div class="row g-3">
                    @foreach ($board->lists as $list)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <input type="checkbox" class="btn-check" id="list-{{ $list->id }}" name="lists[]"
                                value="{{ $list->id }}" @checked($list->state == 'active') autocomplete="off">

                            <label class="btn btn-outline-secondary w-100 rounded-pill py-2"
                                for="list-{{ $list->id }}">
                                <i class="bi bi-check2 me-1"></i>
                                {{ $list->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- Lists Settings --}}
            <div class="col bg-secondary-subtle border p-3 rounded-4 shadow-lg border-secondary">

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-gear-fill me-2 fs-4"></i>
                    <h4 class="mb-0">Lists Settings</h4>
                </div>

                <div class="list-group">
                    @foreach ($board->lists as $list)
                        @if ($list->state == 'active')
                            <button
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $list->id }}">

                                <span>
                                    <i class="bi bi-trello me-2"></i>
                                    {{ $list->name }}
                                </span>

                                @if ($list->start_date != null && $list->days != [])
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        Problem
                                    </span>
                                @endif
                            </button>
                        @endif
                    @endforeach
                </div>

                <div id="listsAccordion" class="mt-3">
                    @foreach ($board->lists as $list)
                        @if ($list->state == 'active')
                            <div class="collapse" id="collapse-{{ $list->id }}" data-bs-parent="#listsAccordion">

                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">

                                        <h5 class="card-title mb-3">
                                            {{ $list->name }}
                                        </h5>

                                        <x-form.field type="date" id="weeks[{{ $list->id }}][start_date]"
                                            label="Data de Início" :value="$list->start_date" />

                                        <div class="border rounded-3 p-3">

                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-calendar-week me-2"></i>
                                                <span class="fw-semibold">
                                                    Dias da Semana
                                                </span>
                                            </div>

                                            <div class="d-flex flex-wrap justify-content-center gap-2">

                                                @foreach ($weeks as $week)
                                                    <input type="checkbox" class="btn-check"
                                                        id="{{ $list->name . $week }}"
                                                        name="weeks[{{ $list->id }}][days][]"
                                                        value="{{ $week }}" autocomplete="off"
                                                        @checked(in_array($week, $list->days ?? []))> <label
                                                        class="btn btn-outline-secondary px-4 py-2"
                                                        for="{{ $list->name . $week }}">{{ $week }}</label>
                                                @endforeach

                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div
            class="mt-5 bg-secondary-subtle border text p-4 rounded-4 shadow-lg border-secondary
            row gap-3 d-flex align-content-center justify-content-center">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-bar-chart-line-fill me-2 fs-4"></i>
                <h4 class="mb-0">Statistics Settings:</h4>
            </div>

            {{-- The 3 "+" btns --}}
            @for ($i = 0; $i < 3; $i++)
                <div id="{{ $i }}" class="btn-div col-md justify-content-center d-flex">
                    @if (isset($board->stats[$i]))
                        <x-admin.stat :divPos="$i" :labels="$labels" :stats="$board->stats" />
                    @else
                        <div class="plus-btn">
                            <button type="button" class="btn btn-info">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endfor

            <script>
                document.addEventListener('click', async (e) => {
                    // Add a stat group
                    const btn = e.target.closest('.plus-btn');
                    if (!btn) return;

                    const payload = {
                        view: 'components.admin.stat',
                        data: {
                            divPos: btn.parentElement.id,
                            labels: @json($labels),
                            board: @json($board),
                        }
                    };

                    const request = await fetch("{{ route('getview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    });

                    const html = await request.text();
                    const parent = btn.parentElement;
                    const parentId = parent.id;
                    parent.innerHTML = html;

                    // "activate" the script in the request
                    parent.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');

                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                        } else {
                            newScript.textContent = oldScript.textContent;
                        }

                        document.body.appendChild(newScript);
                    });
                });
            </script>
        </div>
    </x-form>
</x-layout>
