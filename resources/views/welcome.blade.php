<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SmartHub Management System</title>
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    
        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-950 text-slate-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <header class="mb-10 flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.32em] text-sky-400/80">Smart Hub Tracker</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-white">Lacak jadwal peminjaman ruang</h1>
                    <p class="mt-3 max-w-2xl text-slate-400">Gunakan halaman ini untuk melihat jadwal aktif, beralih antara peminjaman ruangan, serta mencari berdasarkan nama, item, atau status.</p>
                </div>
                @if (Route::has('login'))
                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-100 transition hover:bg-slate-800">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-100 transition hover:bg-slate-800">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-100 transition hover:bg-slate-800">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </header>

            {{-- <section class="grid gap-4 md:grid-cols-3 mb-10">
                <article class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/30">
                    <p class="text-xs uppercase tracking-[0.32em] text-sky-300/70">Ruangan</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $roomBorrowings->count() }}</p>
                    <p class="mt-3 text-sm text-slate-400">Jadwal peminjaman ruangan aktif yang tersedia.</p>
                </article>
                <article class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/30">
                    <p class="text-xs uppercase tracking-[0.32em] text-fuchsia-300/70">Peralatan</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $equipmentBorrowings->count() }}</p>
                    <p class="mt-3 text-sm text-slate-400">Jadwal peminjaman peralatan aktif yang tersedia.</p>
                </article>
                <article class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/30">
                    <p class="text-xs uppercase tracking-[0.32em] text-emerald-300/70">Approved</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $roomBorrowings->where('status', 'approved')->count() + $equipmentBorrowings->where('status', 'approved')->count() }}</p>
                    <p class="mt-3 text-sm text-slate-400">Jumlah peminjaman yang disetujui dari ruangan dan peralatan.</p>
                </article>
            </section> --}}

            <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/30">
                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-sky-300/80">Filter</p>
                        <h2 class="mt-2 text-2xl font-semibold text-white">Cari jadwal</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <button type="button" data-tab="room" class="tab-button active rounded-full border border-slate-700 bg-slate-800 px-4 py-2 text-slate-100 transition hover:bg-slate-700">Ruangan</button>
                        {{-- <button type="button" data-tab="equipment" class="tab-button rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-100 transition hover:bg-slate-700">Peralatan</button> --}}
                    </div>
                </div>

                <div class="mb-6">
                    <label for="scheduleSearch" class="mb-2 block text-sm font-medium text-slate-300">Cari jadwal</label>
                    <input id="scheduleSearch" type="search" placeholder="Cari ruang, peminjam, atau status..." class="w-full rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20" />
                </div>

                <div id="room" class="tab-panel">
                    @if ($roomBorrowings->isEmpty())
                        <div class="rounded-3xl border border-dashed border-slate-700 p-6 text-slate-400">Belum ada jadwal peminjaman ruangan yang aktif.</div>
                    @else
                        <div class="space-y-4">
                            @foreach ($roomBorrowings as $borrowing)
                                <article class="rounded-3xl border border-slate-800 bg-slate-950/95 p-5 shadow-inner shadow-slate-950/20"
                                data-search="{{ strtolower($borrowing->room->name . ' ' . $borrowing->user->name . ' ' . $borrowing->status . ' ' . ($borrowing->notes ?? '')) }}"
                                data-detail='@json($borrowing->detail_data)'>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm uppercase tracking-[0.2em] text-sky-300/80">{{ $borrowing->room->name }}</p>
                                            <h3 class="mt-2 text-xl font-semibold text-white">{{ $borrowing->user->name }}</h3>
                                        </div>
                                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-[0.22em] text-slate-300">{{ ucfirst($borrowing->status) }}</span>
                                    </div>
                                    <p class="mt-4 text-sm text-slate-400">{{ $borrowing->start_date->format('d M Y H:i') }} – {{ $borrowing->end_date->format('d M Y H:i') }}</p>
                                    <button
                                        type="button"
                                        class="mt-4 inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:bg-slate-800 detail-button"
                                        data-id="{{ $borrowing->id }}"
                                    >Lihat detail</button>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div id="equipment" class="tab-panel hidden">
                    @if ($equipmentBorrowings->isEmpty())
                        <div class="rounded-3xl border border-dashed border-slate-700 p-6 text-slate-400">Belum ada jadwal peminjaman peralatan yang aktif.</div>
                    @else
                        <div class="space-y-4">
                            @foreach ($equipmentBorrowings as $borrowing)
                                <article class="rounded-3xl border border-slate-800 bg-slate-950/95 p-5 shadow-inner shadow-slate-950/20"
                                data-search="{{ strtolower($borrowing->equipment->pluck('name')->join(', ') . ' ' . $borrowing->user->name . ' ' . $borrowing->status . ' ' . ($borrowing->notes ?? '')) }}"
                                data-detail='@json($borrowing->detail_data)'>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm uppercase tracking-[0.2em] text-fuchsia-300/80">{{ $borrowing->equipment->pluck('name')->join(', ') }}</p>
                                            <h3 class="mt-2 text-xl font-semibold text-white">{{ $borrowing->user->name }}</h3>
                                        </div>
                                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs uppercase tracking-[0.22em] text-slate-300">{{ ucfirst($borrowing->status) }}</span>
                                    </div>
                                    <p class="mt-4 text-sm text-slate-400">{{ $borrowing->start_date->format('d M Y H:i') }} – {{ $borrowing->end_date->format('d M Y H:i') }}</p>
                                    <button
                                        type="button"
                                        class="mt-4 inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:bg-slate-800 detail-button"
                                        data-id="{{ $borrowing->id }}"
                                    >Lihat detail</button>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- <p class="mt-6 text-sm leading-6 text-slate-400">Gunakan tab di atas untuk beralih antara jadwal ruangan dan peralatan. Hasil akan difilter saat mengetik.</p> --}}
            </section>
        </div>

        <div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-8">
            <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] border border-slate-800 bg-slate-900 shadow-2xl">
                <button id="closeDetailModal" type="button" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-slate-200 transition hover:bg-slate-700">×</button>
                <div class="p-8">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-sky-300/80">Detail Peminjaman</p>
                            <h2 class="mt-2 text-3xl font-semibold text-white">Informasi Peminjaman</h2>
                        </div>
                        <span id="modalStatus" class="rounded-full bg-slate-800 px-4 py-2 text-sm uppercase tracking-[0.2em] text-slate-300"></span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Peminjam</p>
                            <p id="modalBorrower" class="mt-2 text-lg font-semibold text-white"></p>
                            <p id="modalBorrowerEmail" class="text-sm text-slate-400"></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Ruang</p>
                            <p id="modalRoom" class="mt-2 text-lg font-semibold text-white"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Periode</p>
                            <p id="modalPeriod" class="mt-2 text-lg font-semibold text-white"></p>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Catatan</p>
                            <p id="modalNotes" class="mt-2 text-slate-300 whitespace-pre-line"></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Peralatan</p>
                            <ul id="modalEquipmentList" class="mt-2 space-y-2 text-slate-300"></ul>
                        </div>
                    </div>

                    <div class="mt-8">
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-400">Riwayat Check-in</p>
                        <ul id="modalCheckIns" class="mt-2 space-y-2 text-slate-300"></ul>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const tabButtons = document.querySelectorAll('[data-tab]');
                const panels = document.querySelectorAll('.tab-panel');
                const searchInput = document.getElementById('scheduleSearch');
                const detailModal = document.getElementById('detailModal');
                const closeModalButton = document.getElementById('closeDetailModal');
                const modalStatus = document.getElementById('modalStatus');
                const modalBorrower = document.getElementById('modalBorrower');
                const modalBorrowerEmail = document.getElementById('modalBorrowerEmail');
                const modalRoom = document.getElementById('modalRoom');
                const modalPeriod = document.getElementById('modalPeriod');
                const modalNotes = document.getElementById('modalNotes');
                const modalEquipmentList = document.getElementById('modalEquipmentList');
                const modalCheckIns = document.getElementById('modalCheckIns');

                function updateActiveTab(tabKey) {
                    tabButtons.forEach((button) => {
                        const active = button.dataset.tab === tabKey;
                        button.classList.toggle('active', active);
                        button.classList.toggle('bg-slate-800', active);
                        button.classList.toggle('bg-slate-900', !active);
                    });
                    panels.forEach((panel) => panel.classList.toggle('hidden', panel.id !== tabKey));
                    filterResults();
                }

                function filterResults() {
                    const query = searchInput.value.trim().toLowerCase();
                    const activePanel = document.querySelector('.tab-panel:not(.hidden)');
                    if (!activePanel) return;
                    activePanel.querySelectorAll('[data-search]').forEach((item) => {
                        item.style.display = item.dataset.search.includes(query) ? '' : 'none';
                    });
                }

                function openDetailModal(detail) {
                    modalStatus.textContent = detail.status;
                    modalBorrower.textContent = detail.borrower_name;
                    modalBorrowerEmail.textContent = detail.borrower_email;
                    modalRoom.textContent = detail.room;
                    modalPeriod.textContent = `${detail.start_date} – ${detail.end_date}`;
                    modalNotes.textContent = detail.notes;

                    modalEquipmentList.innerHTML = detail.equipment.length
                        ? detail.equipment.map((item) => `<li class="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3"><span class="font-medium text-white">${item.name}</span><span class="block text-sm text-slate-400">Qty: ${item.qty}</span></li>`).join('')
                        : '<li class="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-400">Tidak ada peralatan.</li>';

                    modalCheckIns.innerHTML = detail.check_ins.length
                        ? detail.check_ins.map((item) => `<li class="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3"><span class="font-medium text-white">${item.equipment}</span><span class="block text-sm text-slate-400">${item.checked_in_at}</span></li>`).join('')
                        : '<li class="rounded-2xl border border-slate-700 bg-slate-950/80 px-4 py-3 text-slate-400">Belum ada check-in.</li>';

                    detailModal.classList.remove('hidden');
                }

                function closeDetailModal() {
                    detailModal.classList.add('hidden');
                }

                document.querySelectorAll('.detail-button').forEach((button) => {

                    button.addEventListener('click', async () => {

                        const borrowingId = button.dataset.id;

                        try {

                            const response = await fetch(`/public-borrowings/${borrowingId}`);

                            const detail = await response.json();

                            openDetailModal(detail);

                        } catch (error) {

                            console.error(error);

                            alert('Gagal mengambil detail peminjaman.');

                        }
                    });

                });

                closeModalButton.addEventListener('click', closeDetailModal);
                detailModal.addEventListener('click', (event) => {
                    if (event.target === detailModal) {
                        closeDetailModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeDetailModal();
                    }
                });

                tabButtons.forEach((button) => button.addEventListener('click', () => updateActiveTab(button.dataset.tab)));
                searchInput.addEventListener('input', filterResults);
                updateActiveTab('room');
            })();
        </script>
    </body>
</html>
