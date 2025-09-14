<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroomingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroomingController extends Controller
{
    public function index()
    {
        $services = GroomingService::latest()->paginate(10);
        return view('admin.grooming-services.index', compact('services'));
    }

    public function show(GroomingService $service)
    {
        return view('admin.grooming-services.show', compact('service'));
    }

    public function destroy(GroomingService $service)
    {
        // hapus gambar jika ada
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('admin.grooming-services.index')
                         ->with('success', 'Layanan Grooming berhasil dihapus');
    }
}
