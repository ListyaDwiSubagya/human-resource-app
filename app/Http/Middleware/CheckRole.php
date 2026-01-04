<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {

        $employeeID = auth()->user()->employee_id;
        $employee = Employee::find($employeeID);

        $request->session()->put('role', $employee->role->title);
        $request->session()->put('employee_id', $employee->id);

        if(!in_array($employee->role->title, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
