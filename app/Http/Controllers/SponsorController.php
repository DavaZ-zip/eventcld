<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::withCount('events')->latest()->paginate(10);
        return view('sponsors.index', compact('sponsors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'contribution_amount' => 'required|numeric|min:0',
            'sponsorship_level' => 'required|in:platinum,gold,silver,bronze',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor = Sponsor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sponsor berhasil ditambahkan!',
            'sponsor' => $sponsor
        ]);
    }

    public function show(Sponsor $sponsor)
    {
        $sponsor->load('events');
        return response()->json($sponsor);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'contribution_amount' => 'required|numeric|min:0',
            'sponsorship_level' => 'required|in:platinum,gold,silver,bronze',
        ]);

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $validated['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sponsor berhasil diupdate!',
            'sponsor' => $sponsor->fresh()
        ]);
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo) {
            Storage::disk('public')->delete($sponsor->logo);
        }

        $sponsor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sponsor berhasil dihapus!'
        ]);
    }
}
