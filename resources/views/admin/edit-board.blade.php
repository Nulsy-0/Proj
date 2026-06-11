@props(['board', 'weeks'])

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

                <button data-bs-toggle="modal" data-bs-target="#delete-board" class="btn btn-danger">
                    Delete
                    <i class="bi bi-trash3-fill"></i>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="delete-board" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Board</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                                <p>
                                    By pressing "Delete" this Board ({{ $board->name }}) will no longer exists!
                                    <b>Are o shore that you want to delete it?</b>
                                </p>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                <x-form id="board-delete" method="DELETE" :action="route('board.delete', ['id' => $board->id])">
                                    <input name="id" type="number" value="{{ $board->id }}" hidden readonly>
                                    <button data-bs-toggle="modal" data-bs-target="delete-board"
                                        class="btn btn-danger">Delete <i class="bi bi-trash3-fill"></i></button>
                                </x-form>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <div class="p-3 pt-2 pb-1 border rounded-3">
                                        <p class="pb-2 border-bottom ">{{ $list->name }}</p>

                                        <x-form.field label="Start date" id="weeks[{{ $list->id }}][start_date]"
                                            type="date" :value="$list->start_date" />

                                        <div
                                            class="border rounded-3 p-2 mb-2 @error('weeks') border-danger-subtle @enderror form-control">
                                            <div class="d-flex justify-content-between">
                                                <p class="ms-2 mb-2 text-muted small">Week days</p>
                                                @error('weeks')
                                                    <i class="bi bi-exclamation-circle text-danger"></i>
                                                @enderror
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                                @foreach ($weeks as $week)
                                                    <input type="checkbox" class="btn-check"
                                                        id="{{ $list->name . $week }}"
                                                        name="weeks[{{ $list->id }}][days][]"
                                                        value="{{ $week }}" autocomplete="off"
                                                        @checked(in_array($week, $list->days ?? []))>
                                                    <label class="btn btn-outline-secondary px-4 py-2"
                                                        for="{{ $list->name . $week }}">{{ $week }}</label>
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
        </div>
    </x-form>
</x-layout>
