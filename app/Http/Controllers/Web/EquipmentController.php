<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Http\Requests\StoreEquipmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipment = Equipment::paginate(10);

        return view('equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('equipment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request)
    {
        $data = $request->validated();

        // Handle kategori lainnya
        if (
            $request->category == 'Lainnya' &&
            $request->filled('other_category')
        ) {
            $data['category'] = $request->other_category;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                                    ->store('equipment', 'public');
        }

        Equipment::create($data);

        return redirect()->route('equipment.index')
                        ->with('success', 'Peralatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEquipmentRequest $request, Equipment $equipment)
    {
        $data = $request->validated();

        // Handle kategori lainnya
        if (
            $request->category == 'Lainnya' &&
            $request->filled('other_category')
        ) {
            $data['category'] = $request->other_category;
        }

        // Handle image upload
        if ($request->hasFile('image')) {

            // Delete old image
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }

            $data['image'] = $request->file('image')
                                    ->store('equipment', 'public');
        }

        $equipment->update($data);

        return redirect()->route('equipment.index')
                        ->with('success', 'Peralatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        // Delete image if exists
        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('equipment.index')
                        ->with('success', 'Peralatan berhasil dihapus.');
    }
}