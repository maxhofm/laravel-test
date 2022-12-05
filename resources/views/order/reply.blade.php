<x-app-layout>
    <x-form :action="route('orders.sendReply')" class="w-full">
        @csrf

        @include('components.flash-alert')

        <div class="rounded overflow-hidden shadow-lg">
            <div class="px-12 py-12">
                <div>
                    <div>
                        <div class="font-bold text-xl mb-2">
                            <span>Заявка {{ $order->id }} ({{$order->status->title}})</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 flex items-center">Тема заявки:</p>
                    <p class="text-gray-700 text-base">{{ $order->title }}</p>
                </div>
                <hr>
                <br>
                <p class="text-sm text-gray-600 flex items-center">Клиент:</p>
                <p class="text-gray-700 text-base">{{ $order->client->name }}</p>
                <p class="text-gray-700 text-base">{{ $order->client->email }}</p>
                <hr>
                <br>
                <p class="text-sm text-gray-600 flex items-center">Файл:</p>
                <p class="text-gray-700 text-base">Отсутствует</p>
                <hr>
                <br>
                <p class="text-sm text-gray-600 flex items-center">Создана:</p>
                <p class="text-gray-700 text-base">{{ $order->created_at }}</p>
                <hr>
                <br>
                <p class="text-sm text-gray-600 flex items-center">Сообщение:</p>
                <p class="text-gray-700 text-base">{{ $order->text }}</p>
                <hr>
                <br>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <x-input-label for="text" value="Ответ"/>
                        <x-textarea id="text" class="block mt-1 w-full" name="reply" :value="old('text')" required autofocus/>
                        <x-input-error :messages="$errors->get('reply')" class="mt-2"/>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $order->id }}">

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <x-primary-button class="p-4 bg-blue-500">
                            Отправить
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</x-app-layout>
