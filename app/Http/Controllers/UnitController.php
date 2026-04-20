<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Employee;
use App\Http\Requests\UnitRequest;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request)
    {
        $units = Unit::withCount('activeEmployees')
            ->search($request->search)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created unit.
     */
    public function store(UnitRequest $request)
    {
        Unit::create($request->validated());

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified unit.
     */
    public function show(Request $request, Unit $unit)
    {
        $employees = $unit->employees()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('pf_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('units.show', compact('unit', 'employees'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified unit.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(Unit $unit)
    {
        if ($unit->employees()->count() > 0) {
            return redirect()->route('units.index')
                ->with('error', 'Cannot delete unit with assigned employees.');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
