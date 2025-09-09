<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with(['creator', 'participants', 'sessions'])
            ->latest()
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
        ]);

        $validated['created_by'] = Auth::id();

        $event = Event::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dibuat!',
            'event' => $event->load('creator')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['sessions.speaker', 'participants', 'sponsors']);
        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil diupdate!',
            'event' => $event->fresh()->load('creator')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dihapus!'
        ]);
    }

    public function register(Request $request, Event $event)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
        ]);

        if ($event->participants()->where('participant_id', $validated['participant_id'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah terdaftar di acara ini!'
            ], 422);
        }

        if ($event->registered_count >= $event->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Acara sudah penuh!'
            ], 422);
        }

        $event->participants()->attach($validated['participant_id'], [
            'registration_date' => now(),
            'status' => 'registered'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendaftar ke acara!'
        ]);
    }
}
