<x-app-layout>
    <div class="p-4">
        <x-form action="#" class="w-full max-w-lg" has-files>
            @csrf

            @include('components.flash-alert')

            <!-- Title -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    {{--                <x-input-label for="title" :value="__('Title')" />--}}
                    <x-input-label for="title" value="Тема"/>
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                </div>
            </div>


            <!-- Text -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <x-input-label for="text" value="Сообщение"/>
                    <x-textarea id="text" class="block mt-1 w-full" name="text" :value="old('text')" required autofocus/>
                    <x-input-error :messages="$errors->get('text')" class="mt-2"/>
                </div>
            </div>

            <!-- File -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <x-input-label for="file" value="Прикрепить файл"/>
                    <x-text-input id="file" class="block mt-1 w-full" type="file" name="file" />
                    <x-input-error :messages="$errors->get('text')" class="mt-2"/>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <x-primary-button action="#" class="p-4 bg-blue-500">
                        Отправить
                    </x-primary-button>
                </div>
            </div>
        </x-form>
    </div>
</x-app-layout>
