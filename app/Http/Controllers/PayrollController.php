<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index() {
        
        $payrolls = Payroll::all(); 

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
