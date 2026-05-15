<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Peralatan') }}
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

                    <form method="POST" action="{{ route('equipment.update', $equipment) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Peralatan</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Kategori
                            </label>

                            @php
                                $categories = [
                                    'Kamera',
                                    'Lensa Kamera',
                                    'Tripod',
                                    'Lighting Studio',
                                    'Mikrofon',
                                    'Audio Recorder',
                                    'Speaker',
                                    'Green Screen',
                                    'Komputer Editing',
                                    'Proyektor',
                                    'Streaming Equipment'
                                ];

                                $currentCategory = old('category', $equipment->category);

                                $isCustomCategory = !in_array($currentCategory, $categories);
                            @endphp

                            <select name="category"
                                    id="category"
                                    onchange="toggleOtherCategory()"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('category') border-red-500 @enderror"
                                    required>

                                <option value="">Pilih Kategori</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ $currentCategory == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach

                                <option value="Lainnya"
                                    {{ $isCustomCategory ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>

                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Kategori Lain -->
                        <div id="other-category-container"
                            class="mb-4 {{ $isCustomCategory ? '' : 'hidden' }}">

                            <label for="other_category"
                                class="block text-sm font-medium text-gray-700">
                                Tulis Kategori Lain
                            </label>

                            <input type="text"
                                name="other_category"
                                id="other_category"
                                value="{{ $isCustomCategory ? $currentCategory : '' }}"
                                placeholder="Masukkan kategori..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Stock -->
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $equipment->stock) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('stock') border-red-500 @enderror"
                                   min="1" required>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <!-- Condition -->
                        <div class="mb-4">
                            <label for="condition" class="block text-sm font-medium text-gray-700">Kondisi</label>
                            <select name="condition" id="condition"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('condition') border-red-500 @enderror">
                                <option value="good" {{ old('condition', $equipment->condition) == 'good' ? 'selected' : '' }}>Bagus</option>
                                <option value="damaged" {{ old('condition', $equipment->condition) == 'damaged' ? 'selected' : '' }}>Rusak</option>
                                <option value="lost" {{ old('condition', $equipment->condition) == 'lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <!-- Status -->
                        {{-- <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror">
                                <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="borrowed" {{ old('status', $equipment->status) == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Foto Peralatan</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Upload foto peralatan baru untuk mengganti foto lama (opsional)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($equipment->image)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700">Foto Saat Ini</p>
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Foto Peralatan" class="mt-2 max-h-48 rounded-md border border-gray-200" />
                            </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                                      placeholder="Deskripsi detail peralatan, spesifikasi, dll">{{ old('description', $equipment->description) }}</textarea>
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
                                Perbarui Peralatan
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

    window.onload = toggleOtherCategory;
</script>
</x-app-layout>
