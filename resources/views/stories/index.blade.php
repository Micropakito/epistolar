@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis historias
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <a href="{{ route('stories.create') }}" class="underline text-blue-600">
                Crear nueva historia
            </a>

            <div class="mt-4 bg-white shadow sm:rounded-lg p-4">
                @if ($stories->isEmpty())
                    <p>Todavía no has creado ninguna historia.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($stories as $story)
                            <li class="border-b pb-2 flex items-start justify-between gap-4">
                                <div>
                                    <strong>{{ $story->title }}</strong><br>
                                    <a href="{{ route('stories.show', $story) }}"
                                    class="text-sm text-blue-600 hover:underline">
                                        Ver historia
                                    </a>
                                    <small>
                                        Visibilidad: {{ $story->visibility }}
                                        · Estado: {{ $story->status }}
                                        · Creada el {{ $story->created_at->format('d/m/Y') }}
                                    </small><br>
                                     <a href="{{ route('stories.characters.index', $story) }}"
                                        class="text-sm text-blue-600 hover:underline">
                                            Ver personajes
                                      </a>
                                    @if ($story->description)
                                        <span>
                                            {{ Str::limit(strip_tags($story->description), 200) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <a href="{{ route('stories.edit', $story) }}"
                                    class="text-blue-600 hover:underline">
                                        Editar
                                    </a>

                                    <form action="{{ route('stories.destroy', $story) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Seguro que quieres eliminar esta historia? Esta acción no se puede deshacer.');">
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
