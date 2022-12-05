<x-app-layout>
    @include('components.flash-alert')

    @if (!empty($orders))
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Тема</th>
                        <th>Сообщение</th>
                        <th>Клиент</th>
                        <th>Файл</th>
                        <th>Дата создания</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="p-2">{{ $order->id }}</td>
                            <td class="p-2">{{ $order->title }}</td>
                            <td class="p-2">{{ $order->text }}</td>
                            <td class="p-2">
                                <div>
                                    <p>{{ $order->client->name }}</p>
                                    <p>{{ $order->client->email }}</p>
                                </div>
                            </td>
                            <td class="p-2">
                                @if ($order->hasFile())
                                    <a href="/download-file/{{ $order->file->path }}">
                                        {{ $order->file->path }}
                                    </a>
                                @endif
                            </td>
                            <td class="p-2">{{ $order->created_at }}</td>
                            <td class="p-2">
                                @if ($order->status->isNew())
                                    <a href="/orders/reply/{{ $order->id }}" class="p-2 bg-blue-500">Ответить</a>
                                @else
                                    <input type="checkbox" name="check">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <div>
            <p>Заявки отсутвуют</p>
        </div>
    @endif
</x-app-layout>
