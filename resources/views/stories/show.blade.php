@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $story->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('stories.index') }}" class="text-blue-600 hover:underline">
                ← Volver a mis historias
            </a>

            <div class="mt-4 bg-white shadow sm:rounded-lg p-4 space-y-4">

               {{-- Descripción --}}
                @if ($story->description)
                    <div class="prose max-w-none">
                        {!! $story->description !!}
                    </div>
                @endif

                {{-- Participantes --}}
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-700 mb-1">Participantes</h3>
                    <ul class="text-gray-600 space-y-1">
                        @foreach ($story->participants as $participant)
                            <li>
                                {{ $participant->name }}
                                @if ($participant->id === $story->creator_id)
                                    <span class="text-xs text-indigo-500">(autor)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Enlace a personajes --}}
                <div class="mt-4">
                    <a class="text-blue-600 hover:underline"
                       href="{{ route('stories.characters.index', $story) }}">
                        Ver personajes
                    </a>
                </div>

                {{-- Acciones --}}
                <div class="flex gap-4 mt-4">
                    <a href="{{ route('stories.edit', $story) }}"
                       class="text-blue-600 hover:underline">
                        Editar
                    </a>

                    <form action="{{ route('stories.destroy', $story) }}"
                          method="POST"
                          onsubmit="return confirm('¿Seguro que quieres eliminar esta historia?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline" type="submit">
                            Borrar
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
