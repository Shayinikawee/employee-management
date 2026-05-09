<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the authenticated employee's profile.
     */
    public function show()
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        $employee->load('unit', 'leaveBalances.leaveType');

        return view('profile.show', compact('employee'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        return view('profile.edit', compact('employee'));
    }

    /**
     * Update the authenticated employee's profile (contact info only).
     */
    public function update(UpdateProfileRequest $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee || !$employee->isApproved()) {
            abort(403, 'Your registration is not yet approved.');
        }

        $employee->update($request->validated());

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
