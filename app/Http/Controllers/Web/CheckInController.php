<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Borrowing;
use App\Models\Equipment;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = now();

        $borrowings = Borrowing::whereNotNull('room_id')
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->with(['user', 'room'])
            ->latest()
            ->get();

        foreach ($borrowings as $borrowing) {
            if ($borrowing->status !== 'completed') {
                if ($now->greaterThan($borrowing->end_date)) {
                    $borrowing->status = 'completed';
                    $borrowing->save();
                } elseif ($now->greaterThanOrEqualTo($borrowing->start_date) && $borrowing->status !== 'active') {
                    $borrowing->status = 'active';
                    $borrowing->save();
                }
            }
        }

        $borrowings = Borrowing::whereNotNull('room_id')
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->with(['user', 'room'])
            ->latest()
            ->paginate(10);

        return view('check-ins.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $borrowings = Borrowing::where('status', 'approved')->with(['user', 'equipment'])->get();
        return view('check-ins.create', compact('borrowings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'equipment_id' => 'required|exists:equipment,id',
        ]);

        // Check if equipment belongs to the borrowing
        $borrowing = Borrowing::find($request->borrowing_id);
        $hasEquipment = $borrowing->equipment()->where('equipment_id', $request->equipment_id)->exists();

        if (!$hasEquipment) {
            return back()->withErrors(['equipment_id' => 'Peralatan ini tidak termasuk dalam peminjaman tersebut.']);
        }

        // Check if already checked in
        $existingCheckIn = CheckIn::where('borrowing_id', $request->borrowing_id)
                                 ->where('equipment_id', $request->equipment_id)
                                 ->first();

        if ($existingCheckIn) {
            return back()->withErrors(['equipment_id' => 'Peralatan ini sudah di-check-in.']);
        }

        CheckIn::create([
            'borrowing_id' => $request->borrowing_id,
            'equipment_id' => $request->equipment_id,
            'user_id' => auth()->id(),
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('check-ins.index')
                        ->with('success', 'Check-in berhasil dilakukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CheckIn $checkIn)
    {
        $checkIn->load(['borrowing.user', 'equipment', 'user']);
        return view('check-ins.show', compact('checkIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CheckIn $checkIn)
    {
        return view('check-ins.edit', compact('checkIn'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CheckIn $checkIn)
    {
        if ($request->status === 'checked_out' && $checkIn->status === 'checked_in') {
            $checkIn->update([
                'status' => 'checked_out',
                'checked_out_at' => now(),
            ]);

            return redirect()->route('check-ins.index')
                            ->with('success', 'Check-out berhasil dilakukan.');
        }

        return back()->withErrors(['status' => 'Status tidak valid.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CheckIn $checkIn)
    {
        $checkIn->delete();

        return redirect()->route('check-ins.index')
                        ->with('success', 'Check-in berhasil dihapus.');
    }
}
