<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Http\Requests\HolidayRequest;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $holidays = Holiday::search($request->search)
            ->byType($request->type)
            ->orderBy('holiday_date', 'desc')
            ->paginate(15)->withQueryString();

        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(HolidayRequest $request)
    {
        Holiday::create($request->validated());
        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    public function edit(Holiday $holiday)
    {
        return view('holidays.edit', compact('holiday'));
    }

    public function update(HolidayRequest $request, Holiday $holiday)
    {
        $holiday->update($request->validated());
        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
