<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengguna') }}
            </h2>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Nama</h3>
                            <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Email</h3>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Role</h3>
                            <p class="mt-1 text-gray-900">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Tanggal Dibuat</h3>
                            <p class="mt-1 text-gray-900">{{ $user->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Peminjaman</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->borrowings->count() }}</p>
                            <p class="text-sm text-gray-500">Total peminjaman yang pernah dibuat</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Check-in</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->checkIns->count() }}</p>
                            <p class="text-sm text-gray-500">Total check-in peralatan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Peminjaman Terbaru</h3>
                            @if($user->borrowings->isEmpty())
                                <p class="text-gray-500">Belum ada peminjaman.</p>
                            @else
                                <ul class="mt-3 space-y-3">
                                    @foreach($user->borrowings->take(5) as $borrowing)
                                        <li class="rounded-lg border border-gray-200 p-4 bg-white">
                                            <p class="font-medium text-gray-900">{{ $borrowing->room?->name ?? 'Peralatan' }}</p>
                                            <p class="text-sm text-gray-500">Tanggal: {{ $borrowing->borrowed_at?->format('d M Y') ?? '-' }}</p>
                                            <p class="text-sm text-gray-500">Status: <span class="font-semibold">{{ ucfirst($borrowing->status) }}</span></p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Check-in Terakhir</h3>
                            @if($user->checkIns->isEmpty())
                                <p class="text-gray-500">Belum ada check-in.</p>
                            @else
                                <ul class="mt-3 space-y-3">
                                    @foreach($user->checkIns->take(5) as $checkIn)
                                        <li class="rounded-lg border border-gray-200 p-4 bg-white">
                                            <p class="font-medium text-gray-900">{{ $checkIn->equipment?->name ?? 'Peralatan' }}</p>
                                            <p class="text-sm text-gray-500">Waktu: {{ $checkIn->checked_in_at?->format('d M Y H:i') ?? '-' }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Edit Pengguna
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition" onclick="return confirm('Hapus pengguna ini?');">
                                Hapus Pengguna
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
