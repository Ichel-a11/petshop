<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroomingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroomingServiceController extends Controller
{
    public function index()
    {
        $services = GroomingService::latest()->paginate(10);
        return view('admin.grooming-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.grooming-services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'duration_minutes' => 'required|integer',
            'pet_type' => 'required|string',
            'pet_size' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('grooming-services', 'public');
        }

        GroomingService::create($validated);

        return redirect()->route('admin.grooming-services.index')
                         ->with('success', 'Layanan Grooming berhasil ditambahkan');
    }

    public function show(GroomingService $groomingService)
    {
        return view('admin.grooming-services.show', compact('groomingService'));
    }

    public function edit(GroomingService $groomingService)
    {
        return view('admin.grooming-services.edit', compact('groomingService'));
    }

    public function update(Request $request, GroomingService $groomingService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'duration_minutes' => 'required|integer',
            'pet_type' => 'required|string',
            'pet_size' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($groomingService->image && Storage::disk('public')->exists($groomingService->image)) {
                Storage::disk('public')->delete($groomingService->image);
            }
            $validated['image'] = $request->file('image')->store('grooming-services', 'public');
        }

        $groomingService->update($validated);

        return redirect()->route('admin.grooming-services.index')
                         ->with('success', 'Layanan Grooming berhasil diperbarui');
    }

    public function destroy(GroomingService $groomingService)
    {
        if ($groomingService->image && Storage::disk('public')->exists($groomingService->image)) {
            Storage::disk('public')->delete($groomingService->image);
        }

        $groomingService->delete();

        return redirect()->route('admin.grooming-services.index')
                         ->with('success', 'Layanan Grooming berhasil dihapus');
    }
}