<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartnerPackage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PartnerPackageController extends Controller
{
    public function index()
    {
        $packages = PartnerPackage::latest()->paginate(20);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:500',
            'type' => 'required|in:transfer,safari,tour',
            'active' => 'sometimes|boolean',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|in:easy,moderate,challenging',
            'group_size_min' => 'nullable|integer|min:1',
            'group_size_max' => 'nullable|integer|min:1',
            'itinerary_text' => 'nullable|string',
            'included_text' => 'nullable|string',
            'excluded_text' => 'nullable|string',
        ]);

        $validated['itinerary'] = array_filter(array_map('trim', explode("\n", $request->itinerary_text ?? '')));
        $validated['included'] = array_filter(array_map('trim', explode("\n", $request->included_text ?? '')));
        $validated['excluded'] = array_filter(array_map('trim', explode("\n", $request->excluded_text ?? '')));
        unset($validated['itinerary_text'], $validated['included_text'], $validated['excluded_text']);
        PartnerPackage::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created.');
    }

    public function edit(PartnerPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, PartnerPackage $package)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'vehicle_type' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:500',
            'type' => 'required|in:transfer,safari,tour',
            'active' => 'sometimes|boolean',
            'duration_days' => 'nullable|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|in:easy,moderate,challenging',
            'group_size_min' => 'nullable|integer|min:1',
            'group_size_max' => 'nullable|integer|min:1',
            'itinerary_text' => 'nullable|string',
            'included_text' => 'nullable|string',
            'excluded_text' => 'nullable|string',
        ]);

        $validated['itinerary'] = array_filter(array_map('trim', explode("\n", $request->itinerary_text ?? '')));
        $validated['included'] = array_filter(array_map('trim', explode("\n", $request->included_text ?? '')));
        $validated['excluded'] = array_filter(array_map('trim', explode("\n", $request->excluded_text ?? '')));
        unset($validated['itinerary_text'], $validated['included_text'], $validated['excluded_text']);
        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated.');
    }

    public function destroy(PartnerPackage $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted.');
    }
}
