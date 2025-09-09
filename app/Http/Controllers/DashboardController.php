<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\EventSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'published')->count(),
            'total_participants' => Participant::count(),
            'total_speakers' => Speaker::count(),
            'total_sponsors' => Sponsor::count(),
            'upcoming_events' => Event::where('start_date', '>', now())->count(),
        ];

        $recent_events = Event::with(['creator', 'participants'])
            ->latest()
            ->take(5)
            ->get();

        $monthly_registrations = Event::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();


        return view('dashboard', compact('stats', 'recent_events', 'monthly_registrations'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
