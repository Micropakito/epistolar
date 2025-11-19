@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Personajes de: {{ $story->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('stories.index') }}" class="text-gray-600 hover:underline">
                    ← Volver a mis historias
                </a>

                <a href="{{ route('stories.characters.create', $story) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Añadir personaje
                </a>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-4">
                @if ($characters->isEmpty())
                    <p>No hay personajes aún en esta historia.</p>
                @else
                    <ul class="space-y-3">
                        @foreach ($characters as $character)
                            <li class="border-b pb-2 flex items-start justify-between gap-4">
                                <div>
                                    <strong>{{ $character->name }}</strong>
                                    <span class="text-xs text-gray-500 ml-2">
                                        ({{ $character->type === 'PC' ? 'PJ' : 'PNJ' }})
                                    </span>
                                    <br>
                                    <small>
                                        Estado:
                                        {{ $character->is_active ? 'Activo' : 'Inactivo' }}
                                    </small>
                                </div>
                                
                                @if ($character->background && $character->background->public_background)
                                    <div class="text-sm text-gray-700 mt-1">
                                        {{ Str::limit(strip_tags($character->background->public_background), 120) }}
                                    </div>
                                @endif

                                <div class="flex flex-col items-end gap-2">
                                    <a href="{{ route('stories.characters.edit', [$story, $character]) }}"
                                       class="text-blue-600 hover:underline">
                                        Editar
                                    </a>

                                    <form action="{{ route('stories.characters.destroy', [$story, $character]) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que quieres eliminar este personaje?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:underline">
                                            Borrar
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
