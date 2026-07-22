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
                    <button type="button" data-bs-toggle="modal" data-bs-target="#delete-user-modal"
                        class="btn btn-danger">
                        Delete
                        <i class="bi bi-trash3-fill"></i>
                    </button>

                    <x-layout.modal id="delete-user" :btn="false" formId="user-delete" head="Delete User"
                        :delete="true">
                        <x-form id="user-delete" method="DELETE" :action="route('user.delete', ['id' => $user->id])">
                            <input name="id" type="number" value="{{ $user->id }}" hidden readonly>
                        </x-form>
                        <p>
                            By pressing "Delete" this User ( <span
                                class="text-decoration-underline">{{ $user->name }}</span> ) will no longer exists!
                        </p>
                        <b>Are o shore that you want to delete it?</b>
                    </x-layout.modal>
                @endif
            </div>
        </div>
    </div>

    <x-form method="PATCH" id="user-edit" :action="route('user.update', ['id' => $user->id])">
        <div class="row mt-5 gap-5">
            <div class="col bg-secondary-subtle border border-secondary rounded-4 shadow-lg p-4">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check2-square fs-4 me-2"></i>
                        <h5 class="mb-0 fw-semibold">Active Boards</h5>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($boards as $board)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <input type="checkbox" class="btn-check" id="board-{{ $board->id }}" name="boards[]"
                                value="{{ $board->id }}" @checked(in_array($board->id, $user->boards)) autocomplete="off">

                            <label class="btn btn-outline-secondary w-100 rounded-pill py-2"
                                for="board-{{ $board->id }}">
                                <i class="bi bi-check2 me-1"></i>
                                {{ $board->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>
            
            <div class="col bg-secondary-subtle border border-secondary rounded-4 shadow-lg p-4">
                <h4 class="mb-3">User Settings:</h4>
                <div class="text-danger d-flex gap-1">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <p>Leave "Reset Password" empty to maintain the same password</p>
                </div>

                <x-form.field id='name' placeholder='Your Name' required :value="$user->name" autocomplete="off" />

                <x-form.field :old="false" id='password_reset' type='password' label='Reset Password'
                    autocomplete="off" />

                <x-form.field :last="true" id='state' type='select' :options="['user', 'admin', 'disabled']" :value="$user->state"
                    required autocomplete="off" />
            </div>
        </div>
    </x-form>

</x-layout>
