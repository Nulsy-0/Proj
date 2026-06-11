@props(['users', 'boards'])

<x-layout>

    {{-- Tabsbtns --}}
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link text-info active" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users"
                type="button" role="tab" aria-controls="nav-users" aria-selected="true">Users</button>
            <button class="nav-link text-info" id="nav-boards-tab" data-bs-toggle="tab" data-bs-target="#nav-boards"
                type="button" role="tab" aria-controls="nav-boards" aria-selected="false">Boards</button>
        </div>
    </nav>

    <div class="tab-content border rounded-bottom-4 border-top-0 bg-secondary-subtle p-3 shadow-lg border-secondary"
        id="nav-tabContent">
        {{-- Users table --}}
        <div class="p-3 pb-0 tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab"
            tabindex="0">

            <x-layout.modal add='user' formId="user">
                <x-form :action="route('register')" method="POST" id="user">
                    @csrf
                    <x-form.field id='name' placeholder='Your Name' required autofocus autocomplete />

                    <x-form.field :old="false" id='password' type='password' placeholder='••••••••' required
                        autofocus autocomplete />

                    <x-form.field :old="false" id='password_confirmation' type='password' label='Comfirm Password'
                        placeholder='••••••••' required autofocus autocomplete />

                    <x-form.field :last="true" id='state' type='select' :options="['user', 'admin', 'disabled']" required autofocus />
                </x-form>
            </x-layout.modal>

            <x-table id="user" :obj="$users" :fields="['id', 'name', 'password', 'state', ['boards'], 'created_at']" />

        </div>

        {{-- Boards table --}}
        <div class="p-3 pb-0 tab-pane fade" id="nav-boards" role="tabpanel" aria-labelledby="nav-boards-tab"
            tabindex="0">

            <x-layout.modal add='board' formId="board">
                <x-form :action="route('board.create')" method="POST" id="board">
                    @csrf
                    <x-form.field :last="true" id='link' placeholder='Board Link' required autofocus autocomplete />
                </x-form>
            </x-layout.modal>

            <x-table id="board" :obj="$boards" :fields="['id', 'name', 'trello_id', 'link', ['lists'], 'created_at']" />
        </div>
    </div>

    <x-debug.errors />
</x-layout>
