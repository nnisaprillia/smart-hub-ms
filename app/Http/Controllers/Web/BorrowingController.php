<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Room;
use App\Models\Equipment;
use App\Http\Requests\StoreBorrowingRequest;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'room'])->latest()->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        $equipment = Equipment::where('status', 'available')->get();
        return view('borrowings.create', compact('rooms', 'equipment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        $borrowing = Borrowing::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Handle equipment if selected
        $equipmentData = $request->input('equipment', []);
        if (is_array($equipmentData)) {
            foreach ($equipmentData as $equipmentId => $item) {
                $quantity = data_get($item, 'quantity');
                $selected = filter_var(data_get($item, 'selected'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                if ($selected && is_numeric($quantity) && $quantity > 0) {
                    $borrowing->equipment()->attach($equipmentId, ['quantity' => (int) $quantity]);
                }
            }
        }

        $redirectRoute = auth()->user()->isAdmin() ? 'borrowings.index' : 'borrowings.mine';

        return redirect()->route($redirectRoute)
                        ->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $borrowing->user_id) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->load(['user', 'room', 'equipment', 'checkIns']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        $rooms = Room::where('status', 'available')->orWhere('id', $borrowing->room_id)->get();
        $equipment = Equipment::where('status', 'available')->orWhereIn('id', $borrowing->equipment->pluck('id'))->get();
        return view('borrowings.edit', compact('borrowing', 'rooms', 'equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBorrowingRequest $request, Borrowing $borrowing)
    {
        $borrowing->update([
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);

        // Update equipment
        $borrowing->equipment()->detach();
        $equipmentData = $request->input('equipment', []);
        if (is_array($equipmentData)) {
            foreach ($equipmentData as $equipmentId => $item) {
                $quantity = data_get($item, 'quantity');
                $selected = filter_var(data_get($item, 'selected'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                if ($selected && is_numeric($quantity) && $quantity > 0) {
                    $borrowing->equipment()->attach($equipmentId, ['quantity' => (int) $quantity]);
                }
            }
        }

        return redirect()->route('borrowings.index')
                        ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        $borrowing->equipment()->detach();
        $borrowing->delete();

        return redirect()->route('borrowings.index')
                        ->with('success', 'Peminjaman berhasil dihapus.');
    }

    /**
     * Approve a borrowing request.
     */
    public function approve(Borrowing $borrowing)
    {
        $borrowing->update(['status' => 'approved']);

        return redirect()->route('borrowings.index')
                        ->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Cancel a borrowing request.
     */
    public function cancel(Borrowing $borrowing)
    {
        $borrowing->update(['status' => 'cancelled']);

        return redirect()->route('borrowings.index')
                        ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    /**
     * Show user's own borrowings.
     */
    public function myBorrowings()
    {
        $borrowings = auth()->user()->borrowings()->with(['room', 'equipment'])->latest()->paginate(10);
        return view('borrowings.my', compact('borrowings'));
    }
}
