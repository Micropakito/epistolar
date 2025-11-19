<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Amigos (status ACCEPTED y donde el usuario es requester o addressee)
        $friends = Friendship::where('status', 'ACCEPTED')
            ->where(function ($q) use ($userId) {
                $q->where('requester_id', $userId)
                  ->orWhere('addressee_id', $userId);
            })
            ->with(['requester', 'addressee'])
            ->get();

        // Solicitudes recibidas pendientes
        $receivedRequests = Friendship::where('status', 'PENDING')
            ->where('addressee_id', $userId)
            ->with('requester')
            ->get();

        // Solicitudes enviadas pendientes
        $sentRequests = Friendship::where('status', 'PENDING')
            ->where('requester_id', $userId)
            ->with('addressee')
            ->get();

        return view('friends.index', compact('friends', 'receivedRequests', 'sentRequests'));
    }

    // Enviar solicitud de amistad buscando por email
    public function store(Request $request)
    {
        $userId = auth()->id();

        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $targetUser = User::where('email', $data['email'])->first();

        if (! $targetUser) {
            return back()->withErrors(['email' => 'No existe ningún usuario con ese email.']);
        }

        if ($targetUser->id === $userId) {
            return back()->withErrors(['email' => 'No puedes enviarte una solicitud a ti mismo.']);
        }

        // ¿Ya existe alguna amistad o solicitud entre ambos?
        $existing = Friendship::where(function ($q) use ($userId, $targetUser) {
                $q->where('requester_id', $userId)
                  ->where('addressee_id', $targetUser->id);
            })
            ->orWhere(function ($q) use ($userId, $targetUser) {
                $q->where('requester_id', $targetUser->id)
                  ->where('addressee_id', $userId);
            })
            ->first();

        if ($existing) {
            return back()->withErrors(['email' => 'Ya existe una amistad o solicitud con ese usuario.']);
        }

        Friendship::create([
            'requester_id' => $userId,
            'addressee_id' => $targetUser->id,
            'status'       => 'PENDING',
        ]);

        return back()->with('status', 'Solicitud de amistad enviada.');
    }

    // Aceptar solicitud recibida
    public function accept(Friendship $friendship)
    {
        $userId = auth()->id();

        // Solo el destinatario puede aceptar
        abort_if($friendship->addressee_id !== $userId, 403);

        $friendship->update([
            'status'       => 'ACCEPTED',
            'responded_at' => now(),
        ]);

        return back()->with('status', 'Solicitud de amistad aceptada.');
    }

    // Rechazar solicitud recibida
    public function decline(Friendship $friendship)
    {
        $userId = auth()->id();

        abort_if($friendship->addressee_id !== $userId, 403);

        $friendship->update([
            'status'       => 'DECLINED',
            'responded_at' => now(),
        ]);

        return back()->with('status', 'Solicitud de amistad rechazada.');
    }

    // Eliminar amistad (para cualquiera de los dos)
    public function destroy(Friendship $friendship)
    {
        $userId = auth()->id();

        abort_if(
            $friendship->requester_id !== $userId
            && $friendship->addressee_id !== $userId,
            403
        );

        $friendship->delete();

        return back()->with('status', 'Amistad eliminada.');
    }
}
