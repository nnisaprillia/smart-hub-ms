<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Peralatan Baru') }}
            </h2>
            <a href="{{ route('equipment.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('equipment.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Peralatan</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block font-medium text-sm text-gray-700">
                                Kategori
                            </label>

                            <select name="category" id="category"
                                onchange="toggleOtherCategory()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('category') border-red-500 @enderror"
                                required>

                                <option value="">Pilih Kategori</option>

                                <option value="Kamera">Kamera</option>
                                <option value="Lensa Kamera">Lensa Kamera</option>
                                <option value="Tripod">Tripod</option>
                                <option value="Lighting Studio">Lighting Studio</option>
                                <option value="Mikrofon">Mikrofon</option>
                                <option value="Audio Recorder">Audio Recorder</option>
                                <option value="Speaker">Speaker</option>
                                <option value="Green Screen">Green Screen</option>
                                <option value="Komputer Editing">Komputer Editing</option>
                                <option value="Proyektor">Proyektor</option>
                                <option value="Streaming Equipment">Streaming Equipment</option>

                                <option value="Lainnya">Lainnya</option>
                            </select>

                            <!-- Input Tambahan -->
                            <div id="other-category-container" class="mt-3 hidden">
                                <label for="other_category" class="block text-sm font-medium text-gray-700">
                                    Tulis Kategori Lain
                                </label>

                                <input type="text"
                                    name="other_category"
                                    id="other_category"
                                    placeholder="Masukkan kategori..."
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('stock') border-red-500 @enderror"
                                   min="1" required>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Condition -->
                        <div class="mb-4">
                            <label for="condition" class="block text-sm font-medium text-gray-700">Kondisi</label>
                            <select name="condition" id="condition"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('condition') border-red-500 @enderror">
                                <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>Bagus</option>
                                <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                                <option value="lost" {{ old('condition') == 'lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror">
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="borrowed" {{ old('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Foto Peralatan</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Upload foto peralatan (opsional, max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                                      placeholder="Deskripsi detail peralatan, spesifikasi, dll">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('equipment.index') }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Peralatan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleOtherCategory() {
            const category = document.getElementById('category').value;
            const otherContainer = document.getElementById('other-category-container');

            if (category === 'Lainnya') {
                otherContainer.classList.remove('hidden');
            } else {
                otherContainer.classList.add('hidden');
            }
        }

        // supaya tetap muncul setelah validation error
        window.onload = toggleOtherCategory;
    </script>
</x-app-layout>