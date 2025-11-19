<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Añadir personaje a: {{ $story->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="character-form" method="POST" action="{{ route('stories.characters.store', $story) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nombre del personaje</label>
                        <input type="text" name="name"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Tipo</label>
                        <select name="type" class="border rounded px-3 py-2">
                            <option value="PC" {{ old('type') === 'PC' ? 'selected' : '' }}>Personaje jugador (PJ)</option>
                            <option value="NPC" {{ old('type') === 'NPC' ? 'selected' : '' }}>PNJ (no jugador)</option>
                        </select>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Crear personaje
                        </button>

                        <a href="{{ route('stories.characters.index', $story) }}"
                           class="text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Trasfondo público</label>

                        {{-- Textarea REAL que se envía (oculto) --}}
                        <textarea id="public_background" name="public_background" class="hidden">
                            {{ old('public_background') }}
                        </textarea>

                        {{-- Editor visible --}}
                        <div id="public-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('public_background') !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Notas privadas</label>

                        <textarea id="private_notes" name="private_notes" class="hidden">
                            {{ old('private_notes') }}
                        </textarea>

                        <div id="private-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('private_notes') !!}
                        </div>
                    </div>
                    <div class="mb-4">
                    <label class="block font-medium mb-1">Jugador que lleva este personaje</label>
                    <select name="owner_user_id" class="border rounded px-3 py-2 w-full">
                        <option value="">— Sin asignar (PNJ o aún sin dueño) —</option>
                        @foreach(($participants ?? collect()) as $participant)
                            <option value="{{ $participant->id }}"
                                {{ old('owner_user_id') == $participant->id ? 'selected' : '' }}>
                                {{ $participant->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Solo aparecen los amigos/participantes de esta historia.
                    </p>
                </div>
                                </form>
            </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>

    <script>
        const publicEditor = new Quill('#public-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link']
                ]
            }
        });

        const privateEditor = new Quill('#private-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link']
                ]
            }
        });

        const form = document.getElementById('character-form');
        form.addEventListener('submit', function () {
            document.getElementById('public_background').value = publicEditor.root.innerHTML;
            document.getElementById('private_notes').value    = privateEditor.root.innerHTML;
        });
    </script>
    
@endpush


