<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admins::all();
        return response()->json($admins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Admins::create($request->all());
        return response()->json($admin, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admin = Admins::findOrFail($id);
        return response()->json($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = Admins::findOrFail($id);
        $admin->update($request->all());
        return response()->json($admin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = Admins::findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }
}
