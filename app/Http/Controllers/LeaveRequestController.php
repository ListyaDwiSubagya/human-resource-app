<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index() {

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee data not found');
        }
        
        $role = $employee->role?->title;

         if ($role === 'HR'){
             $leaveRequests = LeaveRequest::all();
         } else {
            $leaveRequests = LeaveRequest::where('employee_id', $employee->id)->get();
         }

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create() {
        $employees = Employee::all();

        return view('leave-requests.create', compact('employees'));
    }

    public function store(Request $request) {
        
         $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee data not found');
        }

        $role = $employee->role?->title;

        if ($role === 'HR') {
        
            $request->validate([
                'employee_id' => 'required',
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date'
            ]);

            $request->merge([
                'status' => 'pending'
            ]);

            LeaveRequest::create($request->all());
        } else {
            LeaveRequest::create([
                'employee_id' => $employee->id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date, 
                'status' => 'pending'
            ]);
        }

        return redirect()->route('leave-requests.index')->with('success','Leave Request Created Successfully');
    }

    public function edit(LeaveRequest $leaveRequest) {

        $employees = Employee::all();

        return view('leave-requests.edit', compact('employees', 'leaveRequest'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest) {

        $request->validate([
            'employee_id' => 'required',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $leaveRequest->update($request->all());

        return redirect()->route('leave-requests.index')->with('success','Leave Request Updated Successfully');

    }

    public function confirm($id) {

        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'confirmed'
        ]);

        return redirect()->route('leave-requests.index')->with('success','Leave Request Confirmed Successfully');

    }
    public function reject($id) {

        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('leave-requests.index')->with('success','Leave Request Rejected Successfully');

    }

    public function destroy(LeaveRequest $leaveRequest) {
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success','Leave Request Deleted Successfully');

    }
}
