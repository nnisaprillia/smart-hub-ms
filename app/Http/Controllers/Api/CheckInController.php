<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckInResource;
use App\Models\Borrowing;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $checkIns = CheckIn::with(['borrowing.user', 'equipment', 'user'])
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return CheckInResource::collection($checkIns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'equipment_id' => 'required|exists:equipment,id',
            'notes' => 'nullable|string',
        ]);

        $borrowing = Borrowing::findOrFail($validated['borrowing_id']);

        if (!auth()->user()->isAdmin() && auth()->id() !== $borrowing->user_id) {
            abort(403, 'Akses ditolak.');
        }

        $checkIn = CheckIn::create([
            'borrowing_id' => $borrowing->id,
            'equipment_id' => $validated['equipment_id'],
            'user_id' => auth()->id(),
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil disimpan',
            'data' => new CheckInResource($checkIn->load(['borrowing.user', 'equipment', 'user'])),
        ], 201);
    }

    public function checkOut(CheckIn $checkIn)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $checkIn->user_id) {
            abort(403, 'Akses ditolak.');
        }

        $checkIn->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil diproses',
            'data' => new CheckInResource($checkIn->load(['borrowing.user', 'equipment', 'user'])),
        ]);
    }
}
