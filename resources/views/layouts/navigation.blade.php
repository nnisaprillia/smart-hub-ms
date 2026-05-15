
<!-- Sidebar -->
<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-80 flex-col border-r border-slate-800 bg-slate-900 transition-transform duration-300 lg:static lg:translate-x-0 -translate-x-full"
>
    <!-- Header -->
    <div class="border-b border-slate-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.32em] text-sky-400/80">
                    Smart Hub
                </p>
                <h1 class="mt-1 text-xl font-semibold text-white">
                    Tracker
                </h1>
            </div>

            <button
                id="sidebarToggle"
                type="button"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800 text-slate-400 transition hover:bg-slate-700 hover:text-white lg:flex hidden"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>
        </div>
    </div>

    <!-- User -->
    <div class="border-b border-slate-800 p-6">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-500 font-semibold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-white">
                    {{ Auth::user()->name }}
                </p>

                <p class="truncate text-xs text-slate-400">
                    {{ Auth::user()->email }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto p-6">
        <div class="space-y-2">

            <!-- Dashboard -->
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                {{ request()->routeIs('dashboard')
                    ? 'bg-sky-500 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"
                    />
                </svg>

                Dashboard
            </a>

            @if(Auth::user()->isAdmin())

                <!-- Rooms -->
                <a
                    href="{{ route('rooms.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('rooms.*')
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7"
                        />
                    </svg>

                    Ruang Kerja
                </a>

                <!-- Equipment -->
                <a
                    href="{{ route('equipment.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('equipment.*')
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7"
                        />
                    </svg>

                    Peralatan
                </a>

                <!-- Borrowings -->
                <a
                    href="{{ route('borrowings.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('borrowings.*')
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"
                        />
                    </svg>

                    Peminjaman
                </a>

                <!-- Users -->
                <a
                    href="{{ route('users.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('users.*')
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2m12 0H7"
                        />
                    </svg>

                    Pengguna
                </a>

                <!-- Checkins -->
                <a
                    href="{{ route('check-ins.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                    {{ request()->routeIs('check-ins.*')
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"
                        />
                    </svg>

                    Check-in
                </a>

            @endif

            <!-- Profile -->
            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                {{ request()->routeIs('profile.*')
                    ? 'bg-sky-500 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                </svg>

                Profile
            </a>

        </div>
    </div>

    <!-- Logout -->
    <div class="border-t border-slate-800 p-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-red-500 hover:text-white"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"
                    />
                </svg>

                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Overlay -->
<div
    id="sidebarOverlay"
    class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"
></div>

<!-- Mobile Button -->
<button
    id="mobileSidebarToggle"
    type="button"
    class="fixed left-4 top-4 z-50 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-slate-200 lg:hidden"
>
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
        />
    </svg>
</button>

<!-- Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mobileToggle = document.getElementById('mobileSidebarToggle');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        mobileToggle.addEventListener('click', () => {

            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }

        });

        overlay.addEventListener('click', closeSidebar);

    });
</script>