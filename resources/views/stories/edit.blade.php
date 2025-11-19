<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar historia
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

                <form id="story-form" method="POST" action="{{ route('stories.update', $story) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Título</label>
                        <input type="text" name="title"
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('title', $story->title) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Descripción</label>

                        {{-- Textarea REAL oculto --}}
                        <textarea id="description" name="description" class="hidden">
                            {{ old('description', $story->description) }}
                        </textarea>

                        {{-- Editor visible --}}
                        <div id="quill-editor" class="border rounded" style="min-height: 200px;">
                            {!! old('description', $story->description) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Visibilidad</label>
                        <select name="visibility" class="border rounded px-3 py-2">
                            <option value="PRIVATE"  @selected(old('visibility', $story->visibility) === 'PRIVATE')>Privada</option>
                            <option value="PUBLIC"   @selected(old('visibility', $story->visibility) === 'PUBLIC')>Pública</option>
                            <option value="UNLISTED" @selected(old('visibility', $story->visibility) === 'UNLISTED')>Oculta con enlace</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Estado</label>
                        <select name="status" class="border rounded px-3 py-2">
                            <option value="ACTIVE"    @selected(old('status', $story->status) === 'ACTIVE')>Activa</option>
                            <option value="PAUSED"    @selected(old('status', $story->status) === 'PAUSED')>Pausada</option>
                            <option value="FINISHED"  @selected(old('status', $story->status) === 'FINISHED')>Terminada</option>
                            <option value="ARCHIVED"  @selected(old('status', $story->status) === 'ARCHIVED')>Archivada</option>
                        </select>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                            Guardar cambios
                        </button>

                        <a href="{{ route('stories.index') }}"
                           class="text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Participantes
                        </label>

                        <div class="mt-2 space-y-1">
                            @forelse($friends as $friend)
                                <label class="inline-flex items-center space-x-2">

                                    <input type="checkbox"
                                        name="participants[]"
                                        value="{{ $friend->id }}"
                                        @if($story->participants->contains($friend->id)) checked @endif
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span>{{ $friend->name }}</span>

                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No tienes amigos aún.</p>
                            @endforelse
                        </div>
                    </div>

                </form>
            </div>
        </div>
        </div>
        @push('scripts')
            <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>

            <script>
                const quill = new Quill('#quill-editor', {
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
                    document.getElementById('description').value = quill.root.innerHTML;
                });
            </script>
        @endpush




</x-app-layout>
