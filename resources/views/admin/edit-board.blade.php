@props([
    'board',
    'weeks'
])

<x-layout>
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class=" d-flex align-items-center gap-3">
                <h2 class="mb-0">{{ $board->name }}</h2>

                <button type="submit" form="board-edit-form" class="btn btn-info">Update <i
                        class="bi bi-arrow-repeat"></i></button>
            </div>
        </div>

        <div class="col-md-6 text-md-end">
            <div class="d-flex justify-content-md-end align-items-center gap-3 flex-wrap">

                <p class="mb-0">
                    <strong>Created:</strong>

                    {{ $board->created_at->format('D, d M Y') . ', ' }}

                    <span class="text-decoration-underline">
                        {{ $board->created_at->diffForHumans() }}
                    </span>
                </p>
                <x-form id="board-delete" method="DELETE" :action="route('board.delete', ['id' => $board->id])">
                    <input name="id" type="number" value="{{ $board->id }}" hidden readonly>
                    <button class="btn btn-danger">Delete <i class="bi bi-trash3-fill"></i></button>
                </x-form>
            </div>
        </div>
    </div>

    <x-form method="PATCH" id="board-edit" :action="route('board.update', ['id' => $board->id])">
        <div class="row mt-5 gap-5">
            <div class="col bg-secondary-subtle border p-3 rounded-4 shadow-lg">

                <h4 class="mb-3">Activated lists:</h4>

                <div class="d-flex flex-wrap gap-3">
                    @foreach ($board->lists as $list)
                        <input type="checkbox" class="btn-check" id="list-{{ $list->id }}" name="lists[]"
                            value="{{ $list->id }}" @checked($list->state == 'active') autocomplete="off">

                        <label class="btn btn-outline-secondary px-4 py-2" for="list-{{ $list->id }}">
                            {{ $list->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="col bg-secondary-subtle border p-3 rounded-4 shadow-lg">
                <h4 class="mb-3">Lists Settings:</h4>

                <div class="dropdown mb-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Lists
                    </button>

                    <ul class="dropdown-menu">
                        @foreach ($board->lists as $list)
                            <li>
                                <button class="dropdown-item {{ $list->state == 'disabled' ? 'disabled pe-none' : '' }}"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $list->id }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $list->id }}">
                                    {{ $list->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div id="listsAccordion">
                        @foreach ($board->lists as $key => $list)
                            @if ($list->state == 'active')
                                <div class="collapse mt-2" id="collapse-{{ $list->id }}"
                                    data-bs-parent="#listsAccordion">
                                    <div class="p-3 pt-2 pb-1 border rounded-3 @error('days') is-invalid @enderror">
                                        <p class="pb-2 border-bottom ">{{ $list->name }}</p>

                                        <x-form.field label="Start date" id="days[{{ $list->id }}][start_date]"
                                            type="date" :value="$list->start_date" />

                                        <div class="border rounded-3 p-2 mb-2 form-control">
                                            <p class="ms-2 mb-2 text-muted small">Week days</p>
                                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                                @foreach ($weeks as $week)
                                                    <input type="checkbox" class="btn-check"
                                                        id="days-{{ $list->name . $week }}"
                                                        name="days[{{ $list->id }}][weeks][]"
                                                        value="{{ $week }}" autocomplete="off"
                                                        @checked(in_array($week, $list->days ?? []))>
                                                    <label class="btn btn-outline-secondary px-4 py-2"
                                                        for="days-{{ $list->name . $week }}">{{ $week }}</label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>

            <x-debug.errors />
        </div>
    </x-form>
</x-layout>
