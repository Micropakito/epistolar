<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    // Lista de historias del usuario logueado
    public function index()
    {
        $stories = Story::where('creator_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stories.index', compact('stories'));
    }

    // Formulario de nueva historia
    public function create()
    {
        $friends = auth()->user()->friends();  // <- con paréntesis
        return view('stories.create', compact('friends'));
    }
    // Guardar nueva historia
    public function store(Request $request)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'visibility'   => ['nullable', 'string'], // ajusta si es required
            'status'       => ['nullable', 'string'], // ajusta si es required
            'participants'   => ['nullable', 'array'],
            'participants.*' => ['exists:users,id'],
        ]);

        $user = Auth::user();

        $story = Story::create([
            'title'       => $request->title,
            'description' => $request->description,
            'visibility'  => $request->visibility,
            'status'      => $request->status,
            'creator_id'  => $user->id,   // <-- usamos creator_id, igual que en index/destroy
        ]);

        $participantIds = $request->input('participants', []);

        // Aseguramos que el creador siempre esté como participante
        if (! in_array($user->id, $participantIds)) {
            $participantIds[] = $user->id;
        }

        $story->participants()->sync($participantIds);

        return redirect()
            ->route('stories.show', $story)
            ->with('status', 'Historia creada y amigos añadidos.');
    }

    // Ver una historia
    public function show(Story $story)
    {
        // Solo el creador puede verla (ajusta si quieres otra lógica)
        abort_if($story->creator_id !== Auth::id(), 403);

        return view('stories.show', compact('story'));
    }

    // Formulario de edición
    public function edit(Story $story)
    {
        abort_if($story->creator_id !== auth()->id(), 403);

        $friends = auth()->user()->friends();  // <- con paréntesis

        return view('stories.edit', compact('story', 'friends'));
    }


    // Actualizar historia
    public function update(Request $request, Story $story)
    {
        abort_if($story->creator_id !== Auth::id(), 403);

        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'visibility'   => ['nullable', 'string'],
            'status'       => ['nullable', 'string'],
            'participants'   => ['nullable', 'array'],
            'participants.*' => ['exists:users,id'],
        ]);

        $story->update([
            'title'       => $request->title,
            'description' => $request->description,
            'visibility'  => $request->visibility,
            'status'      => $request->status,
        ]);

        $participantIds = $request->input('participants', []);

        // El creador siempre dentro
        if (! in_array($story->creator_id, $participantIds)) {
            $participantIds[] = $story->creator_id;
        }

        $story->participants()->sync($participantIds);

        return redirect()
            ->route('stories.show', $story)
            ->with('status', 'Historia actualizada.');
    }

    // Borrar historia
    public function destroy(Story $story)
    {
        abort_if($story->creator_id !== Auth::id(), 403);

        $story->delete();

        return redirect()
            ->route('stories.index')
            ->with('status', 'Historia eliminada.');
    }
}
