<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SmartHub Management System</title>
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="flex min-h-screen">

            {{-- Sidebar --}}
            @include('layouts.navigation')

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col overflow-hidden">

                {{-- Mobile Header --}}
                <div class="lg:hidden border-b border-slate-800 bg-slate-900 p-4">
                    <button
                        id="mobileSidebarToggle"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-slate-200"
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
                </div>

                {{-- Header --}}
                @isset($header)
                    <header class="border-b border-gray-200 bg-white shadow-sm">
                        <div class="px-6 py-5">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                {{-- Content --}}
                <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>
