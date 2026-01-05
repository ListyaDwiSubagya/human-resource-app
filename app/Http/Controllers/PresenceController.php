<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function index() {

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee data not found');
        }

        $role = $employee->role?->title;

        if ($role === 'HR') {
            // HR bisa lihat semua presensi
            $presences = Presence::with('employee')->get();
        } else {
            // selain HR hanya lihat presensi sendiri
            $presences = Presence::where('employee_id', $employee->id)->get();
        }

        return view('presences.index', compact('presences'));
        
    }

    public function create() {

        $employees = Employee::all();

        return view('presences.create', compact('employees'));
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
                'check_in' => 'required',
                'check_out' => 'required',
                'date' => 'required|date',
                'status' => 'required|string'
            ]);

            Presence::create($request->all());
        } else {

            $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            ]);

            Presence::create([
                'employee_id' => $employee->id,
                'check_in' => Carbon::now(),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'date' => Carbon::now(),
                'status' => 'present'
            ]);
        }
        

        return redirect()->route('presences.index')->with('success', 'Presence recorded successfully');
    }

    public function edit(Presence $presence) {
        $employees = Employee::all();

        return view('presences.edit', compact('presence', 'employees'));
    }

    public function update(Request $request ,Presence $presence){
        $request->validate([
            'employee_id' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'date' => 'required|date',
            'status' => 'required|string'
        ]);

        $presence->update($request->all());

        return redirect()->route('presences.index')->with('success', 'Presence updated successfully');
        
    }
    
    public function destroy(Presence $presence) {
        $presence->delete();

        return redirect()->route('presences.index')->with('success', 'Presence deleted successfully');
    }
}
