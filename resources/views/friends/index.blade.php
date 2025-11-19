<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Amigos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Enviar nueva solicitud --}}
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Enviar solicitud de amistad</h3>

                <form method="POST" action="{{ route('friends.store') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="email" name="email"
                           placeholder="Email del usuario"
                           class="flex-1 border rounded px-3 py-2"
                           value="{{ old('email') }}" required>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                        Enviar solicitud
                    </button>
                </form>
            </div>

            {{-- Solicitudes recibidas --}}
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Solicitudes recibidas</h3>

                @if ($receivedRequests->isEmpty())
                    <p class="text-gray-600 text-sm">No tienes solicitudes pendientes.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($receivedRequests as $req)
                            <li class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <strong>{{ $req->requester->name }}</strong>
                                    <span class="text-xs text-gray-500 ml-2">
                                        ({{ $req->requester->email }})
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('friends.accept', $req) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded">
                                            Aceptar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('friends.decline', $req) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="px-3 py-1 bg-gray-400 hover:bg-gray-500 text-white text-sm rounded">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Solicitudes enviadas --}}
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Solicitudes enviadas</h3>

                @if ($sentRequests->isEmpty())
                    <p class="text-gray-600 text-sm">No tienes solicitudes enviadas pendientes.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($sentRequests as $req)
                            <li class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <strong>{{ $req->addressee->name }}</strong>
                                    <span class="text-xs text-gray-500 ml-2">
                                        ({{ $req->addressee->email }})
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    Pendiente de respuesta
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Lista de amigos --}}
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-3">Tus amigos</h3>

                @if ($friends->isEmpty())
                    <p class="text-gray-600 text-sm">Todavía no tienes amigos añadidos.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($friends as $friendship)
                            @php
                                $friend = $friendship->requester_id === auth()->id()
                                    ? $friendship->addressee
                                    : $friendship->requester;
                            @endphp
                            <li class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <strong>{{ $friend->name }}</strong>
                                    <span class="text-xs text-gray-500 ml-2">
                                        ({{ $friend->email }})
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('friends.destroy', $friendship) }}"
                                      onsubmit="return confirm('¿Seguro que quieres eliminar a este amigo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
