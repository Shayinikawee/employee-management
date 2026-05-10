<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is authenticated and is staff
        if (!$user || !$user->isStaff()) {
            return $next($request);
        }

        // Check if employee exists and is approved
        $employee = $user->employee;

        if (!$employee) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Employee record not found.']);
        }

        if ($employee->approval_status !== 'approved') {
            return redirect()->route('registration.pending')
                ->with('warning', 'Your registration is ' . $employee->approval_status . '.');
        }

        return $next($request);
    }
}
