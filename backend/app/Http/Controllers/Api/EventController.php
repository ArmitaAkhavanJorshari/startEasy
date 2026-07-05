<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::latest()->get());
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'date' => 'required|string|max:255',
        'time' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $event = new Event();

    $event->title = $validated['title'];
    $event->date = $validated['date'];
    $event->time = $validated['time'];
    $event->location = $validated['location'];
    $event->description = $validated['description'] ?? null;
    $event->participants = 0;

    $event->save();

    return response()->json($event, 201);
}


    public function join($id)
    {
        $event = Event::findOrFail($id);

        $event->participants = ($event->participants ?? 0) + 1;
        $event->save();

        return response()->json($event);
    }
}