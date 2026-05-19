<x-layout>
    <div>
        <x-form :action="route('register')" method='POST' title='Register' subtitle="The Beginning">

            <x-form.field id='name' placeholder='Your Name' required autofocus autocomplete />

            <x-form.field old="false" id='password' type='password' placeholder='••••••••' required autofocus
                autocomplete />

            <x-form.field old="false" id='password_confirmation' type='password' label='Comfirm Password'
                placeholder='••••••••' required autofocus autocomplete />

            <x-form.field id='type' type='options' :options="['user', 'admin', 'disabled']" required autofocus />
            
        </x-form>

    </div>
</x-layout>