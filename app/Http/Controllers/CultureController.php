<?php

namespace App\Http\Controllers;

use App\Http\Requests\Culture\StoreCultureRequest;
use App\Http\Requests\Culture\UpdateCultureRequest;
use App\Models\Culture;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class CultureController extends Controller
{
    /**
     * Display a listing of cultures with search support
     */
    public function index()
    {
        $search = request('search');

        $cultures = Culture::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('detail', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();   // Important: preserves search term in pagination links

        return view('admin.cultures.index', compact('cultures'));
    }

    /**
     * Show the form for creating a new culture
     */
    public function create()
    {
        return view('admin.cultures.create');
    }

    /**
     * Store a newly created culture
     */
    public function store(StoreCultureRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cultures', 'public_direct');
        }

        Culture::create($data);

        // return redirect()->route('admin.cultures.index')
        //                  ->with('message', 'Culture created successfully.');
    }

    /**
     * Show the form for editing the specified culture
     */
    public function edit(Culture $culture)
    {
        return view('admin.cultures.edit', compact('culture'));
    }

    /**
     * Update the specified culture
     */
    public function update(UpdateCultureRequest $request, Culture $culture)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($culture->image) {
                Storage::disk('public_direct')->delete($culture->image);
            }
            $data['image'] = $request->file('image')->store('cultures', 'public_direct');
        }

        $culture->update($data);

        // return redirect()->route('admin.cultures.index')
        //                  ->with('message', 'Culture updated successfully.');
    }

    /**
     * Remove the specified culture
     */
    public function destroy(Culture $culture)
    {
        if ($culture->image) {
            Storage::disk('public_direct')->delete($culture->image);
        }

        $culture->delete();

        return redirect()->route('admin.cultures.index')
                         ->with('message', 'Culture deleted successfully.');
    }
}