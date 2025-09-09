<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::withCount('events')->latest()->paginate(10);
        return view('participants.index', compact('participants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'phone' => 'nullable|string',
            'organization' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        $participant = Participant::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil ditambahkan!',
                'participant' => $participant
            ]);
        }

        return redirect()->route('participants.index')->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function show(Participant $participant)
    {
        $participant->load(['events', 'sessions']);
        
        if (request()->wantsJson()) {
            return response()->json($participant);
        }
        
        return view('participants.show', compact('participant'));
    }

    public function update(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email,' . $participant->id,
            'phone' => 'nullable|string',
            'organization' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        $participant->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil diupdate!',
                'participant' => $participant->fresh()
            ]);
        }

        return redirect()->route('participants.index')->with('success', 'Peserta berhasil diupdate!');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil dihapus!'
            ]);
        }

        return redirect()->route('participants.index')->with('success', 'Peserta berhasil dihapus!');
    }
}