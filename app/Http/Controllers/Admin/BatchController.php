<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::withCount('students')->latest()->get();
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.batches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        Batch::create($request->all());
        return redirect()->route('admin.batches.index')
            ->with('success', 'Batch created successfully!');
    }

    public function edit(Batch $batch)
    {
        return view('admin.batches.edit', compact('batch'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $batch->update($request->all());
        return redirect()->route('admin.batches.index')
            ->with('success', 'Batch updated successfully!');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.batches.index')
            ->with('success', 'Batch deleted successfully!');
    }

    public function show(Batch $batch)
    {
        return redirect()->route('admin.batches.index');
    }
}