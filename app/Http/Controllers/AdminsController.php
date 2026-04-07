<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all(['idAdmin','nom','prenom','email','created_at']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'prenom'  => 'required|string|max:255',
            'email'   => 'required|email|unique:admins,email',
            'password'=> 'required|string|min:8',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $admin = Admin::create($validated);
        return response()->json($admin, 201);
    }

    public function show($id)
    {
        return response()->json(Admin::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $validated = $request->validate([
            'nom'    => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email'  => 'sometimes|email|unique:admins,email,' . $id,
        ]);
        $admin->update($validated);
        return response()->json($admin);
    }

    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
