<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        return response()->json(
            Advertisement::latest()->get()
        );
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'image_url' => 'nullable|string',
        ]);

        $advertisement = Advertisement::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'contact' => $validated['contact'],
            'image_url' => $validated['image_url'] ?? null,
        ]);

        return response()->json($advertisement, 201);
    }
}