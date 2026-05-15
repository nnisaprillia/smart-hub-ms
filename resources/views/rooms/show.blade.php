<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Ruang: ') . $room->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rooms.edit', $room) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('rooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Room Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Ruang</h3>

                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Ruang</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kapasitas</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->capacity }} orang</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->location ?? '-' }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($room->status === 'available') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $room->status === 'available' ? 'Tersedia' : 'Maintenance' }}
                                        </span>
                                    </dd>
                                </div>

                                @if($room->description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->description }}</dd>
                                </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->created_at->format('d/m/Y H:i') }}</dd>
                                </div>

                                @if($room->updated_at != $room->created_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Update</dt>
                                    <dd class="text-sm text-gray-900">{{ $room->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Recent Borrowings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Peminjaman Terbaru</h3>

                            @php
                                $recentBorrowings = $room->borrowings()->with('user')->latest()->take(5)->get();
                            @endphp

                            @if($recentBorrowings->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentBorrowings as $borrowing)
                                    <div class="border rounded-lg p-3">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $borrowing->user->name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $borrowing->start_date->format('d/m/Y H:i') }} - {{ $borrowing->end_date->format('d/m/Y H:i') }}
                                                </p>
                                                @if($borrowing->notes)
                                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($borrowing->notes, 50) }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($borrowing->status === 'approved') bg-green-100 text-green-800
                                                @elseif($borrowing->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($borrowing->status === 'active') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($borrowing->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Belum ada peminjaman untuk ruang ini</p>
                            @endif
                        </div>

                    </div>

                    <!-- Delete Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                Hapus ruang ini secara permanen. Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruang ini? Semua data terkait akan hilang.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Hapus Ruang
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>