@props(['boards', 'user'])

<x-layout>
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class=" d-flex align-items-center gap-3">
                <h2 class="mb-0">{{ $user->name }}</h2>

                <button type="submit" form="user-edit-form" class="btn btn-info">Update <i
                        class="bi bi-arrow-repeat"></i></button>
            </div>
        </div>

        <div class="col-md-6 text-md-end">
            <div class="d-flex justify-content-md-end align-items-center gap-3 flex-wrap">

                <p class="mb-0">
                    <strong>Created:</strong>

                    {{ $user->created_at->format('D, d M Y') . ', ' }}

                    <span class="text-decoration-underline">
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </p>
                @if ($user->state != 'admin')
                    <button data-bs-toggle="modal" data-bs-target="#delete-user" class="btn btn-danger">
                        Delete
                        <i class="bi bi-trash3-fill"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="delete-user" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modael title</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    By pressing 
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                    <x-form id="user-delete" method="DELETE" :action="route('user.delete', ['id' => $user->id])">
                                        <input name="id" type="number" value="{{ $user->id }}" hidden
                                            readonly>
                                        <button data-bs-toggle="modal" data-bs-target="delete-user"
                                            class="btn btn-danger">Delete <i class="bi bi-trash3-fill"></i></button>
                                    </x-form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-form method="PATCH" id="user-edit" :action="route('user.update', ['id' => $user->id])">
        <div class="row mt-5 gap-5">
            <div class="col bg-secondary-subtle border p-3 rounded-4 shadow-lg">

                <h4 class="mb-3">Activated Boards:</h4>

                <div class="d-flex flex-wrap gap-3">
                    @foreach ($boards as $board)
                        <input type="checkbox" class="btn-check" id="list-{{ $board->id }}" name="boards[]"
                            value="{{ $board->id }}" @checked(in_array($board->id, $user->boards)) autocomplete="off">

                        <label class="btn btn-outline-secondary px-4 py-2" for="list-{{ $board->id }}">
                            {{ $board->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="col bg-secondary-subtle border p-3 rounded-4 shadow-lg">
                <h4 class="mb-3">User Settings:</h4>

                <x-form.field id='name' placeholder='Your Name' required autofocus :value="$user->name" />

                <x-form.field :old="false" id='password_reset' type='password' label='Reset Password'
                    placeholder='••••••••' autofocus />

                <x-form.field :last="true" id='state' type='select' :options="['user', 'admin', 'disabled']" :value="$user->state"
                    required autofocus />
            </div>
        </div>
    </x-form>

</x-layout>
