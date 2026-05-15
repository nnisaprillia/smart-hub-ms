<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;

class HomeController extends Controller
{
    public function index()
    {
        $roomBorrowings = Borrowing::with([
                'user',
                'room',
                'equipment',
                'checkIns.equipment'
            ])
            ->whereNotNull('room_id')
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->latest('start_date')
            ->limit(20)
            ->get()
            ->map(function ($borrowing) {

                $borrowing->detail_data = [
                    'borrower_name' => $borrowing->user->name,
                    'borrower_email' => $borrowing->user->email,
                    'status' => ucfirst($borrowing->status),
                    'room' => $borrowing->room->name,
                    'start_date' => $borrowing->start_date->format('d/m/Y H:i'),
                    'end_date' => $borrowing->end_date->format('d/m/Y H:i'),
                    'notes' => $borrowing->notes ?: '-',

                    'equipment' => $borrowing->equipment
                        ->map(function ($item) {
                            return [
                                'name' => $item->name,
                                'qty' => $item->pivot->quantity,
                            ];
                        })
                        ->values(),

                    'check_ins' => $borrowing->checkIns
                        ->map(function ($checkIn) {
                            return [
                                'equipment' => optional($checkIn->equipment)->name ?: 'Peralatan',
                                'checked_in_at' => $checkIn->checked_in_at
                                    ? $checkIn->checked_in_at->format('d/m/Y H:i')
                                    : '-',
                            ];
                        })
                        ->values(),
                ];

                return $borrowing;
            });

        $equipmentBorrowings = Borrowing::with([
                'user',
                'room',
                'equipment',
                'checkIns.equipment'
            ])
            ->whereNull('room_id')
            ->whereIn('status', ['approved', 'active', 'completed'])
            ->latest('start_date')
            ->limit(20)
            ->get()
            ->map(function ($borrowing) {

                $borrowing->detail_data = [
                    'borrower_name' => $borrowing->user->name,
                    'borrower_email' => $borrowing->user->email,
                    'status' => ucfirst($borrowing->status),
                    'room' => optional($borrowing->room)->name ?: 'Peralatan saja',
                    'start_date' => $borrowing->start_date->format('d/m/Y H:i'),
                    'end_date' => $borrowing->end_date->format('d/m/Y H:i'),
                    'notes' => $borrowing->notes ?: '-',

                    'equipment' => $borrowing->equipment
                        ->map(function ($item) {
                            return [
                                'name' => $item->name,
                                'qty' => $item->pivot->quantity,
                            ];
                        })
                        ->values(),

                    'check_ins' => $borrowing->checkIns
                        ->map(function ($checkIn) {
                            return [
                                'equipment' => optional($checkIn->equipment)->name ?: 'Peralatan',
                                'checked_in_at' => $checkIn->checked_in_at
                                    ? $checkIn->checked_in_at->format('d/m/Y H:i')
                                    : '-',
                            ];
                        })
                        ->values(),
                ];

                return $borrowing;
            });

        return view('welcome', compact(
            'roomBorrowings',
            'equipmentBorrowings'
        ));
    }
    public function showBorrowing(Borrowing $borrowing)
    {
        $borrowing->load([
            'user',
            'room',
            'equipment',
            'checkIns.equipment'
        ]);

        return response()->json([
            'borrower_name' => $borrowing->user->name,
            'borrower_email' => $borrowing->user->email,

            'status' => ucfirst($borrowing->status),

            'room' => $borrowing->room
                ? $borrowing->room->name
                : 'Peralatan saja',

            'start_date' => $borrowing->start_date->format('d/m/Y H:i'),

            'end_date' => $borrowing->end_date->format('d/m/Y H:i'),

            'notes' => $borrowing->notes ?: '-',

            'equipment' => $borrowing->equipment->map(function ($item) {
                return [
                    'name' => $item->name,
                    'qty' => $item->pivot->quantity,
                ];
            })->values(),

            'check_ins' => $borrowing->checkIns->map(function ($checkIn) {
                return [
                    'equipment' => optional($checkIn->equipment)->name
                        ?: 'Peralatan',

                    'checked_in_at' => $checkIn->checked_in_at
                        ? $checkIn->checked_in_at->format('d/m/Y H:i')
                        : '-',
                ];
            })->values(),
        ]);
    }
}