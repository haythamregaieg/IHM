<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::all();
        return response()->json($regions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pays' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
        ]);

        $region = Region::create($validated);
        return response()->json($region, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $region = Region::findOrFail($id);
        return response()->json($region);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        $validated = $request->validate([
            'pays' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:255',
        ]);

        $region->update($validated);
        return response()->json($region);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();
        return response()->json(null, 204);
    }
}
