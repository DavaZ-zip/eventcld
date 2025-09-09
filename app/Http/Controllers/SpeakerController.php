<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::withCount('sessions')->latest()->paginate(10);
        return view('speakers.index', compact('speakers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'required|string',
            'email' => 'required|email|unique:speakers,email',
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expertise' => 'nullable|string',
            'social_media' => 'nullable|array',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('speakers', 'public');
        }

        $speaker = Speaker::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Speaker berhasil ditambahkan!',
            'speaker' => $speaker
        ]);
    }

    public function show(Speaker $speaker)
    {
        $speaker->load('sessions.event');
        return response()->json($speaker);
    }

    public function update(Request $request, Speaker $speaker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'required|string',
            'email' => 'required|email|unique:speakers,email,' . $speaker->id,
            'phone' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expertise' => 'nullable|string',
            'social_media' => 'nullable|array',
        ]);

        if ($request->hasFile('photo')) {
            if ($speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
            $validated['photo'] = $request->file('photo')->store('speakers', 'public');
        }

        $speaker->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Speaker berhasil diupdate!',
            'speaker' => $speaker->fresh()
        ]);
    }

    public function destroy(Speaker $speaker)
    {
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return response()->json([
            'success' => true,
            'message' => 'Speaker berhasil dihapus!'
        ]);
    }
}
