<?php

namespace App\Http\Controllers;

use App\Models\EventSession;
use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;

class EventSessionController extends Controller
{
    public function index()
    {
        $sessions = EventSession::with(['event', 'speaker', 'participants'])
                           ->latest()
                           ->paginate(10);
        $events = Event::all();
        $speakers = Speaker::all();
        
        return view('sessions.index', compact('sessions', 'events', 'speakers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'speaker_id' => 'required|exists:speakers,id',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
        ]);

        $session = EventSession::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil dibuat!',
            'session' => $session->load(['event', 'speaker'])
        ]);
    }

    public function show(EventSession $session)
    {
        $session->load(['event', 'speaker', 'participants']);
        return response()->json($session);
    }

    public function update(Request $request, EventSession $session)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'speaker_id' => 'required|exists:speakers,id',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
        ]);

        $session->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil diupdate!',
            'session' => $session->fresh()->load(['event', 'speaker'])
        ]);
    }

    public function destroy(EventSession $session)
    {
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil dihapus!'
        ]);
    }
}