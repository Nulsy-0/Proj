@props([
    'user' => auth()->user(),
])

<x-layout>
    <div class="px-5 mx-5">
        <h1 class="mb-4">Boards:</h1>

        <div class="card bg-secondary-subtle border p-3 rounded-3 shadow-lg border-secondary">
            @forelse ($user->boards() as $board)
                <div class="card-header bg-secondary-subtle">
                    <h4 class="mb-0 text-secondary">{{ $board->name }}</h4>
                </div>

                <div class="card-body bg-secondary-subtle">
                    <div class="row g-3">
                        @foreach ($user->lists() as $list)
                            @if ($list->board_id == $board->id)
                                <div class="col">
                                    <a href="{{ route('list.index', ['id' => $list]) }}" class="text-decoration-none">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body text-center">
                                                <h6 class="mb-0">
                                                    {{ $list->name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                No boards have been linked to your profile.
                <small class="text-muted">Ask Admin ;)</small>
            @endforelse
        </div>
    </div>

</x-layout>
