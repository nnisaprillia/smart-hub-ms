<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return RoomResource::collection(Room::orderBy('name')->get());
    }

    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance',
            'description' => 'nullable|string',
        ]);

        $room = Room::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ruang berhasil dibuat',
            'data' => new RoomResource($room),
        ], 201);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ruang berhasil diperbarui',
            'data' => new RoomResource($room),
        ]);
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ruang berhasil dihapus',
        ]);
    }
}
