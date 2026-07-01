<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCatalog::query();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                ->orWhere('key', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $services = $query->orderBy('sort_order')->orderBy('id')->paginate(15)->withQueryString();

        $stats = [
            'total'    => ServiceCatalog::count(),
            'active'   => ServiceCatalog::where('is_active', true)->count(),
            'inactive' => ServiceCatalog::where('is_active', false)->count(),
        ];

        return view('admin.service-catalog.index', compact('services', 'stats'));
    }

    public function create()
    {
        $nextOrder = (ServiceCatalog::max('sort_order') ?? 0) + 1;
        return view('admin.service-catalog.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'         => ['required', 'string', 'max:50', 'alpha_dash'],
            'label'       => ['required', 'string', 'max:100'],
            'price'       => ['required', 'integer', 'min:0'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $key = Str::slug($data['key'], '_');

        $exists = ServiceCatalog::where('key', $key)->exists();
        if ($exists) {
            return back()->withErrors(['key' => 'Key "' . $key . '" sudah digunakan.'])->withInput();
        }

        $isActive   = $request->boolean('is_active', true);
        $sortOrder  = $data['sort_order'] ?? 0;

        ServiceCatalog::create([
            'key'         => $key,
            'label'       => $data['label'],
            'price'       => $data['price'],
            'icon'        => $data['icon'] ?? null,
            'sort_order'  => $sortOrder,
            'is_active'   => $isActive,
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.service-catalog.index')
            ->with('success', ' Layanan <strong>' . $data['label'] . '</strong> berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('admin.service-catalog.edit', $id);
    }

    public function edit($id)
    {
        $service = ServiceCatalog::findOrFail($id);

        $activeBookingsCount = Booking::whereNotNull('additional_services')
            ->whereIn('status', ['pending', 'approved', 'active', 'waiting_list'])
            ->get()
            ->filter(fn($b) => isset($b->additional_services[$service->key]))
            ->count();

        return view('admin.service-catalog.edit', compact('service', 'activeBookingsCount'));
    }

    public function update(Request $request, $id)
    {
        $service = ServiceCatalog::findOrFail($id);

        $data = $request->validate([
            'key'         => ['required', 'string', 'max:50', 'alpha_dash'],
            'label'       => ['required', 'string', 'max:100'],
            'price'       => ['required', 'integer', 'min:0'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $key = Str::slug($data['key'], '_');

        $exists = ServiceCatalog::where('key', $key)->where('id', '!=', $id)->exists();
        if ($exists) {
            return back()->withErrors(['key' => 'Key "' . $key . '" sudah digunakan layanan lain.'])->withInput();
        }

        $isActive  = $request->boolean('is_active', true);
        $sortOrder = $data['sort_order'] ?? $service->sort_order ?? 0;

        $service->update([
            'key'          => $key,
            'label'        => $data['label'],
            'price'        => $data['price'],
            'icon'         => $data['icon'] ?? null,
            'sort_order'   => $sortOrder,
            'is_active'    => $isActive,
            'name'         => $data['label'],
            'description'  => $data['description'] ?? $service->description,
            'monthly_price'=> $data['price'],
        ]);

        return redirect()->route('admin.service-catalog.index')
            ->with('success', ' Layanan <strong>' . $data['label'] . '</strong> berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = ServiceCatalog::findOrFail($id);

        $inUse = Booking::whereNotNull('additional_services')
            ->whereIn('status', ['pending', 'approved', 'active', 'waiting_list'])
            ->get()
            ->filter(fn($b) => isset($b->additional_services[$service->key]))
            ->count();

        if ($inUse > 0) {
            return back()->with('error',
                ' Layanan <strong>' . $service->label . '</strong> tidak bisa dihapus karena masih digunakan di ' .
                '<strong>' . $inUse . ' booking aktif</strong>. Nonaktifkan saja jika tidak ingin ditampilkan.'
            );
        }

        $label = $service->label;
        $service->delete();

        return redirect()->route('admin.service-catalog.index')
            ->with('success', ' Layanan <strong>' . $label . '</strong> berhasil dihapus.');
    }

    public function toggle($id)
    {
        $service = ServiceCatalog::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success',
            ' Layanan <strong>' . $service->label . '</strong> berhasil ' . $status . '.'
        );
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:service_catalog,id',
        ]);

        foreach ($request->order as $index => $id) {
            ServiceCatalog::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
