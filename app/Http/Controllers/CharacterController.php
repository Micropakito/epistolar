<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Character;
use App\Models\CharacterBackground;
use Illuminate\Http\Request;


class CharacterController extends Controller
{
    // Listar personajes de una historia
    public function index(Story $story)
    {
        $this->authorizeStory($story);

        $characters = $story->characters()->orderBy('name')->get();

        return view('characters.index', compact('story', 'characters'));
    }

    // Formulario crear personaje
    public function create(Story $story)
    {
        $this->authorizeStory($story);

        return view('characters.create', compact('story'));
    }

    public function store(Request $request, Story $story)
    {
        $this->authorizeStory($story);

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'required|in:PC,NPC',
            'public_background' => 'nullable|string',
            'private_notes'     => 'nullable|string',
        ]);

        $character = Character::create([
            'story_id'      => $story->id,
            'owner_user_id' => null,
            'name'          => $data['name'],
            'type'          => $data['type'],
            'is_active'     => true,
        ]);

        CharacterBackground::create([
            'character_id'      => $character->id,
            'public_background' => $data['public_background'] ?? null,
            'private_notes'     => $data['private_notes'] ?? null,
            'last_updated_by'   => auth()->id(),
        ]);

        return redirect()
            ->route('stories.characters.index', $story)
            ->with('status', 'Personaje creado.');
    }



    // Formulario editar personaje
    public function edit(Story $story, Character $character)
    {
        $this->authorizeStory($story);
        $this->assertCharacterBelongsToStory($character, $story);

        return view('characters.edit', compact('story', 'character'));
    }

    // Actualizar personaje
    public function update(Request $request, Story $story, Character $character)
    {
        $this->authorizeStory($story);
        $this->assertCharacterBelongsToStory($character, $story);

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'required|in:PC,NPC',
            'is_active'         => 'nullable|boolean',
            'public_background' => 'nullable|string',
            'private_notes'     => 'nullable|string',
        ]);

        $character->update([
            'name'      => $data['name'],
            'type'      => $data['type'],
            'is_active' => $request->has('is_active'),
        ]);

        $backgroundData = [
            'public_background' => $data['public_background'] ?? null,
            'private_notes'     => $data['private_notes'] ?? null,
            'last_updated_by'   => auth()->id(),
        ];

        if ($character->background) {
            $character->background->update($backgroundData);
        } else {
            $character->background()->create($backgroundData);
        }

        return redirect()
            ->route('stories.characters.index', $story)
            ->with('status', 'Personaje actualizado.');
    }


    // Borrar personaje
    public function destroy(Story $story, Character $character)
    {
        $this->authorizeStory($story);
        $this->assertCharacterBelongsToStory($character, $story);

        $character->delete();

        return redirect()
            ->route('stories.characters.index', $story)
            ->with('status', 'Personaje eliminado.');
    }

    // --- Helpers privados ---

    private function authorizeStory(Story $story): void
    {
        // De momento: solo el creador de la historia puede gestionar personajes
        abort_if($story->creator_id !== auth()->id(), 403);
    }

    private function assertCharacterBelongsToStory(Character $character, Story $story): void
    {
        abort_if($character->story_id !== $story->id, 404);
    }
}
