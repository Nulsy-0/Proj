<x-layout>
    <div>
        <x-form :action="route('login')" method='POST' title='Login' subtitle="Let´s go to work!">

            <x-form.field id='name' label='Name' placeholder='Your Name' required autofocus autocomplete />

            <x-form.field old="false" id='password' type='password' label='Password' placeholder='••••••••' required
                autofocus autocomplete />
        </x-form>
    </div>
</x-layout>