<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Peralatan') }}
            </h2>
            <a href="{{ route('equipment.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Nama</h3>
                                <p class="mt-1 text-gray-700">{{ $equipment->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kategori</h3>
                                <p class="mt-1 text-gray-700">{{ $equipment->category }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Jumlah Stok</h3>
                                <p class="mt-1 text-gray-700">{{ $equipment->stock }}</p>
                            </div>
                            {{-- <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kondisi</h3>
                                <p class="mt-1 text-gray-700">{{ ucfirst($equipment->condition) }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                                <p class="mt-1 text-gray-700">{{ ucfirst($equipment->status) }}</p>
                            </div> --}}
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
                                <p class="mt-1 text-gray-700 whitespace-pre-line">{{ $equipment->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>

                            @if($equipment->image)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Foto</h3>
                                    <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" class="mt-2 w-full rounded-lg border border-gray-200 object-cover max-h-80" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <a href="{{ route('equipment.edit', $equipment) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Edit Peralatan
                        </a>
                        <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition" onclick="return confirm('Hapus peralatan ini?');">
                                Hapus Peralatan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
