<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Dashboard Smart-Hub</h2>
            <div class="text-sm text-gray-500">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Selamat datang, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-blue-100">
                        Role: <span class="font-semibold">
                            {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Member' }}
                        </span>
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-16 h-16 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
        <!-- Admin Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Rooms -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Ruang</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ \App\Models\Room::count() }}</dd>
                        <dd class="text-sm text-green-600 font-medium">
                            {{ \App\Models\Room::where('status', 'available')->count() }} tersedia
                        </dd>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('rooms.index') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium flex items-center">
                        Kelola Ruang
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Equipment -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Peralatan</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ \App\Models\Equipment::count() }}</dd>
                        <dd class="text-sm text-green-600 font-medium">
                            {{ \App\Models\Equipment::where('status', 'available')->count() }} tersedia
                        </dd>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('equipment.index') }}" class="text-sm text-green-600 hover:text-green-500 font-medium flex items-center">
                        Kelola Peralatan
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Active Borrowings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 truncate">Peminjaman Aktif</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ \App\Models\Borrowing::whereIn('status', ['approved', 'active'])->count() }}</dd>
                        <dd class="text-sm text-yellow-600 font-medium">
                            {{ \App\Models\Borrowing::where('status', 'pending')->count() }} menunggu approval
                        </dd>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('borrowings.index') }}" class="text-sm text-yellow-600 hover:text-yellow-500 font-medium flex items-center">
                        Kelola Peminjaman
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</dd>
                        <dd class="text-sm text-purple-600 font-medium">
                            {{ \App\Models\User::where('role', 'admin')->count() }} admin
                        </dd>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('users.index') }}" class="text-sm text-purple-600 hover:text-purple-500 font-medium flex items-center">
                        Kelola Pengguna
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-4">
                    @php
                        $recentBorrowings = \App\Models\Borrowing::with(['user', 'room'])->latest()->take(5)->get();
                    @endphp

                    @forelse($recentBorrowings as $borrowing)
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $borrowing->user->name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                meminjam {{ $borrowing->room ? $borrowing->room->name : 'peralatan' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($borrowing->status === 'approved') bg-green-100 text-green-800
                                @elseif($borrowing->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($borrowing->status === 'active') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas peminjaman</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('rooms.create') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 transition-colors group">
                        <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Tambah Ruang</span>
                    </a>

                    <a href="{{ route('equipment.create') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-green-400 hover:bg-green-50 transition-colors group">
                        <svg class="w-8 h-8 text-gray-400 group-hover:text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Tambah Peralatan</span>
                    </a>

                    <a href="{{ route('borrowings.create') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-yellow-400 hover:bg-yellow-50 transition-colors group">
                        <svg class="w-8 h-8 text-gray-400 group-hover:text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Buat Peminjaman</span>
                    </a>

                    <a href="{{ route('check-ins.index') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-purple-400 hover:bg-purple-50 transition-colors group">
                        <svg class="w-8 h-8 text-gray-400 group-hover:text-purple-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Check-in</span>
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Member Dashboard -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Peminjaman Saya</h3>
                <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Peminjaman Baru
                </a>
            </div>

            @php
                $myBorrowings = Auth::user()->borrowings()->with(['room', 'equipment'])->latest()->take(5)->get();
            @endphp

            @if($myBorrowings->count() > 0)
                <div class="space-y-4">
                    @foreach($myBorrowings as $borrowing)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">
                                    {{ $borrowing->room ? $borrowing->room->name : 'Peminjaman Peralatan' }}
                                </h4>
                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $borrowing->start_date->format('d/m/Y H:i') }}</span>
                                    <span>→</span>
                                    <span>{{ $borrowing->end_date->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($borrowing->notes)
                                <p class="mt-2 text-sm text-gray-600">{{ $borrowing->notes }}</p>
                                @endif
                            </div>
                            <div class="ml-4 flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($borrowing->status === 'approved') bg-green-100 text-green-800
                                    @elseif($borrowing->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($borrowing->status === 'active') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                                @if(in_array($borrowing->status, ['pending']))
                                <form method="POST" action="{{ route('borrowings.cancel', $borrowing) }}" class="inline-block" onsubmit="return confirm('Batalkan peminjaman ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Batalkan
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada peminjaman</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai pinjam ruang atau peralatan untuk kegiatan Anda.</p>
                </div>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>
