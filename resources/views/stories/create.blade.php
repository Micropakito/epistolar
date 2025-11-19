<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear nueva historia
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

                {{-- FORMULARIO CREAR HISTORIA --}}
                <form id="story-form" method="POST" action="{{ route('stories.store') }}">
                    @csrf

                    {{-- Título de la historia --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Título de la historia</label>
                        <input type="text" name="title"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('title') }}" required>
                    </div>

                    {{-- Visibilidad (enum: PUBLIC / PRIVATE / UNLISTED) --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Visibilidad</label>
                        <select name="visibility" class="border rounded px-3 py-2">
                            <option value="PRIVATE" {{ old('visibility', 'PRIVATE') === 'PRIVATE' ? 'selected' : '' }}>
                                Privada
                            </option>
                            <option value="PUBLIC" {{ old('visibility') === 'PUBLIC' ? 'selected' : '' }}>
                                Pública
                            </option>
                            <option value="UNLISTED" {{ old('visibility') === 'UNLISTED' ? 'selected' : '' }}>
                                No listada
                            </option>
                        </select>
                    </div>

                    {{-- Estado (enum: ACTIVE / PAUSED / FINISHED / ARCHIVED) --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Estado</label>
                        <select name="status" class="border rounded px-3 py-2">
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>
                                Activa
                            </option>
                            <option value="PAUSED" {{ old('status') === 'PAUSED' ? 'selected' : '' }}>
                                Pausada
                            </option>
                            <option value="FINISHED" {{ old('status') === 'FINISHED' ? 'selected' : '' }}>
                                Terminada
                            </option>
                            <option value="ARCHIVED" {{ old('status') === 'ARCHIVED' ? 'selected' : '' }}>
                                Archivada
                            </option>
                        </select>
                    </div>

                    {{-- Descripción (Quill) --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Descripción</label>

                        {{-- Campo REAL que se envía (oculto) --}}
                        <textarea id="description" name="description" class="hidden">
                            {{ old('description') }}
                        </textarea>

                        {{-- Editor visible --}}
                        <div id="description-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('description') !!}
                        </div>
                    </div>

                    {{-- Reglas / notas del creador (campo rules) --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Reglas / notas del creador</label>

                        <textarea id="rules" name="rules" class="hidden">
                            {{ old('rules') }}
                        </textarea>

                        <div id="rules-editor" class="border rounded" style="min-height: 160px;">
                            {!! old('rules') !!}
                        </div>
                    </div>

                    {{-- PARTICIPANTES (AMIGOS) --}}
                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Añadir amigos a la historia
                        </label>

                        <div class="mt-2 space-y-1">
                            @forelse(($friends ?? collect()) as $friend)
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox"
                                           name="participants[]"
                                           value="{{ $friend->id }}"
                                           @if(in_array($friend->id, old('participants', []))) checked @endif
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span>{{ $friend->name }}</span>
                                </label>
                                <br>
                            @empty
                                <p class="text-sm text-gray-500">
                                    No tienes amigos aún. Ve a la sección “Amigos” primero 😊
                                </p>
                            @endforelse
                        </div>

                        <x-input-error :messages="$errors->get('participants')" class="mt-2" />
                    </div>

                    {{-- BOTONES --}}
                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
                            Crear historia
                        </button>

                        <a href="{{ route('stories.index') }}"
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
            const descriptionEditor = new Quill('#description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });

            const rulesEditor = new Quill('#rules-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });

            const form = document.getElementById('story-form');
            form.addEventListener('submit', function () {
                document.getElementById('description').value = descriptionEditor.root.innerHTML;
                document.getElementById('rules').value = rulesEditor.root.innerHTML;
            });
        </script>
    @endpush
</x-app-layout>
