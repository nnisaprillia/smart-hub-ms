<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        return EquipmentResource::collection(Equipment::orderBy('name')->get());
    }

    public function show(Equipment $equipment)
    {
        return new EquipmentResource($equipment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:good,damaged,lost',
            'status' => 'required|in:available,borrowed',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment = Equipment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peralatan berhasil ditambahkan',
            'data' => new EquipmentResource($equipment),
        ], 201);
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:good,damaged,lost',
            'status' => 'required|in:available,borrowed',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Peralatan berhasil diperbarui',
            'data' => new EquipmentResource($equipment),
        ]);
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peralatan berhasil dihapus',
        ]);
    }
}
