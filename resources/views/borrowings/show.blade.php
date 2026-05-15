<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Peminjaman') }}
            </h2>
            <a href="{{ auth()->user()->isAdmin() ? route('borrowings.index') : route('borrowings.mine') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Peminjam</h3>
                            <p class="mt-1 text-gray-900">{{ $borrowing->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $borrowing->user->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Status</h3>
                            <p class="mt-1 text-gray-900">{{ ucfirst($borrowing->status) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Ruang</h3>
                            <p class="mt-1 text-gray-900">{{ $borrowing->room ? $borrowing->room->name : 'Peralatan saja' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Tanggal</h3>
                            <p class="mt-1 text-gray-900">{{ $borrowing->start_date->format('d/m/Y H:i') }} - {{ $borrowing->end_date->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Catatan</h3>
                        <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $borrowing->notes ?: '-' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Peralatan</h3>
                        @if($borrowing->equipment->isEmpty())
                            <p class="mt-1 text-gray-900">Tidak ada peralatan.</p>
                        @else
                            <ul class="mt-2 space-y-2">
                                @foreach($borrowing->equipment as $item)
                                    <li class="rounded-lg border border-gray-200 p-3 bg-gray-50">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-900">{{ $item->name }}</span>
                                            <span class="text-sm text-gray-500">Qty: {{ $item->pivot->quantity }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Riwayat Check-in</h3>
                        @if($borrowing->checkIns->isEmpty())
                            <p class="mt-1 text-gray-900">Belum ada check-in.</p>
                        @else
                            <ul class="mt-2 space-y-2">
                                @foreach($borrowing->checkIns as $checkIn)
                                    <li class="rounded-lg border border-gray-200 p-3 bg-gray-50">
                                        <p class="text-sm text-gray-900">{{ $checkIn->equipment?->name ?? 'Peralatan' }}</p>
                                        <p class="text-xs text-gray-500">{{ $checkIn->checked_in_at->format('d/m/Y H:i') }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
