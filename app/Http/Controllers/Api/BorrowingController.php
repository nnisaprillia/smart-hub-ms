<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $borrowings = $user->isAdmin()
            ? Borrowing::with(['user', 'room', 'equipment'])->latest()->get()
            : $user->borrowings()->with(['room', 'equipment'])->latest()->get();

        return BorrowingResource::collection($borrowings);
    }

    public function show(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $borrowing->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return new BorrowingResource($borrowing->load(['user', 'room', 'equipment']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
            'equipment' => 'nullable|array',
            'equipment.*.id' => 'required_with:equipment|exists:equipment,id',
            'equipment.*.quantity' => 'required_with:equipment|integer|min:1',
        ]);

        if (empty($validated['room_id']) && empty($validated['equipment'])) {
            return response()->json([
                'success' => false,
                'message' => 'Room atau equipment harus diisi.',
            ], 422);
        }

        $borrowing = Borrowing::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        if (!empty($validated['equipment'])) {
            $attachData = [];
            foreach ($validated['equipment'] as $equipmentItem) {
                $attachData[$equipmentItem['id']] = ['quantity' => $equipmentItem['quantity']];
            }
            $borrowing->equipment()->attach($attachData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dibuat',
            'data' => new BorrowingResource($borrowing->load(['room', 'equipment'])),
        ], 201);
    }

    public function approve(Borrowing $borrowing)
    {
        $borrowing->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil disetujui',
            'data' => new BorrowingResource($borrowing->load(['user', 'room', 'equipment'])),
        ]);
    }

    public function cancel(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $borrowing->user_id) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dibatalkan',
            'data' => new BorrowingResource($borrowing->load(['user', 'room', 'equipment'])),
        ]);
    }
}
