<?php

namespace App\Http\Controllers;

use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PriestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $priests = Priest::orderBy('name')->paginate(10);
        return view('admin.priests.index', compact('priests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.priests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:priests,email',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'ordination_date' => 'nullable|date',
            'assignment_date' => 'nullable|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('priests', 'public');
            $validated['image'] = $imagePath;
        }

        Priest::create($validated);

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Priest $priest)
    {
        return view('admin.priests.show', compact('priest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Priest $priest)
    {
        return view('admin.priests.edit', compact('priest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Priest $priest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:priests,email,' . $priest->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'ordination_date' => 'nullable|date',
            'assignment_date' => 'nullable|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($priest->image) {
                Storage::disk('public')->delete($priest->image);
            }
            $imagePath = $request->file('image')->store('priests', 'public');
            $validated['image'] = $imagePath;
        }

        $priest->update($validated);

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Priest $priest)
    {
        // Delete image if exists
        if ($priest->image) {
            Storage::disk('public')->delete($priest->image);
        }

        $priest->delete();

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest deleted successfully.');
    }
}
