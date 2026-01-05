<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index() {

        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee data not found');
        }
        
        $role = $employee->role?->title;

        if ($role === 'HR'){
            $payrolls = Payroll::all(); 
        } else
            $payrolls = Payroll::where('employee_id', $employee->id)->get();

        return view('payrolls.index', compact('payrolls'));
    }

    public function create() {
        $employees = Employee::all();

        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request) {

        $request->validate([
            'employee_id' => 'required|numeric',
            'salary' => 'required|numeric',
            'bonuses' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses'); 

        $request->merge(['net_salary' => $netSalary]);
        Payroll::create($request->all());

        return redirect()->route('payrolls.index')->with('success', 'Payroll Created Successfully');
    }

    public function edit(Payroll $payroll) {

        $employees = Employee::all();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll) {

        $request->validate([
            'employee_id' => 'required|numeric',
            'salary' => 'required|numeric',
            'bonuses' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
        ]);

        $netSalary = $request->input('salary') - $request->input('deductions') + $request->input('bonuses'); 

        $request->merge(['net_salary' => $netSalary]);

        $payroll->update($request->all());

        return redirect()->route('payrolls.index')->with('success', 'Payroll updated Successfully');
        
    }

    public function show(Payroll $payroll) {
        return view('payrolls.show', compact('payroll'));
    }
    
    public function destroy(Payroll $payroll) {
        
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted Successfully');
    }
}
