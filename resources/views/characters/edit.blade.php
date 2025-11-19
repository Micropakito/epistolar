<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar personaje en: {{ $story->title }}
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

                <form id="character-form" method="POST" action="{{ route('stories.characters.update', [$story, $character]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nombre del personaje</label>
                        <input type="text" name="name"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('name', $character->name) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Tipo</label>
                        <select name="type" class="border rounded px-3 py-2">
                            <option value="PC" @selected(old('type', $character->type) === 'PC')>Personaje jugador (PJ)</option>
                            <option value="NPC" @selected(old('type', $character->type) === 'NPC')>PNJ (no jugador)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1"
                                   class="mr-2"
                                   {{ old('is_active', $character->is_active) ? 'checked' : '' }}>
                            <span>Personaje activo</span>
                        </label>
                    </div>

                    {{-- Trasfondo público --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Trasfondo público</label>

                        <textarea id="public_background" name="public_background" class="hidden">
                            {{ old('public_background', optional($character->background)->public_background) }}
                        </textarea>

                        <div id="public-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('public_background', optional($character->background)->public_background) !!}
                        </div>
                    </div>

                    {{-- Notas privadas --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Notas privadas</label>

                        <textarea id="private_notes" name="private_notes" class="hidden">
                            {{ old('private_notes', optional($character->background)->private_notes) }}
                        </textarea>

                        <div id="private-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('private_notes', optional($character->background)->private_notes) !!}
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Guardar cambios
                        </button>

                        <a href="{{ route('stories.characters.index', $story) }}"
                           class="text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
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
                document.getElementById('private_notes').value = privateEditor.root.innerHTML;
            });
        </script>
    @endpush
</x-app-layout>

