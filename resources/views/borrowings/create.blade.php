<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Peminjaman Baru') }}
            </h2>
            <a href="{{ auth()->user()->isAdmin() ? route('borrowings.index') : route('borrowings.mine') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('borrowings.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Room Selection -->
                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700">Ruang Kerja</label>
                                <select name="room_id" id="room_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 @error('room_id') border-red-500 @enderror">
                                    <option value="">Pilih Ruang (Opsional)</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }} (Kapasitas: {{ $room->capacity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date and Time -->
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal & Waktu Mulai</label>
                                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 @error('start_date') border-red-500 @enderror"
                                           required>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal & Waktu Selesai</label>
                                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 @error('end_date') border-red-500 @enderror"
                                           required>
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <!-- Equipment Selection -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Peralatan yang Dibutuhkan</label>

                            @if($equipment->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($equipment as $item)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-start space-x-3">
                                            <input type="checkbox" name="equipment[{{ $item->id }}][selected]" id="equipment_{{ $item->id }}"
                                                   value="1"
                                                   {{ old('equipment.' . $item->id . '.selected') ? 'checked' : '' }}
                                                   class="mt-1 equipment-checkbox"
                                                   onchange="toggleQuantity(this, {{ $item->id }})">
                                            <div class="flex-1">
                                                <label for="equipment_{{ $item->id }}" class="cursor-pointer">
                                                    <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $item->category }}</div>
                                                    <div class="text-xs text-gray-400">Stok: {{ $item->stock }}</div>
                                                </label>

                                                <div id="quantity_{{ $item->id }}" class="mt-2 hidden">
                                                    <label class="block text-xs text-gray-600">Jumlah:</label>
                                                    <input type="number" name="equipment[{{ $item->id }}][quantity]"
                                                           class="w-full text-sm border-gray-300 rounded focus:ring-yellow-500 focus:border-yellow-500"
                                                           min="1" max="{{ $item->stock }}" value="{{ old('equipment.' . $item->id . '.quantity', 1) }}">
                                                    @error('equipment.' . $item->id . '.quantity')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">Belum ada peralatan yang tersedia.</p>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 @error('notes') border-red-500 @enderror"
                                      placeholder="Jelaskan keperluan peminjaman...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('borrowings.index') }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleQuantity(checkbox, equipmentId) {
            const quantityDiv = document.getElementById('quantity_' + equipmentId);
            if (checkbox.checked) {
                quantityDiv.classList.remove('hidden');
            } else {
                quantityDiv.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.equipment-checkbox').forEach(function (checkbox) {
                if (checkbox.checked) {
                    const equipmentId = checkbox.id.replace('equipment_', '');
                    document.getElementById('quantity_' + equipmentId).classList.remove('hidden');
                }
            });
        });
    </script>
</x-app-layout>