<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::all();

        // dd($tasks);

        return view('tasks.index', compact('tasks'));
    }

    public function create() {

        $employees = Employee::all();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required',
            'due_date' => 'required|date',
            'status' => 'required|string',
        ]);

        // jika berhasil
        Task::create($validate);
        return redirect()->route('tasks.index')->with('success', 'Task Created Successfully');

    }

    public function show(Task $task){

        Return view('tasks.show', compact('task'));
    }

    public function edit(Task $task) {
        $employees = Employee::all();

        return view('tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task) {
        
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required',
            'due_date' => 'required|date',
            'status' => 'required|string',
        ]);

        // jika berhasil validasi maka update data
        $task->update($validate);
        return redirect()->route('tasks.index')->with('success', 'Task Updated Successfully');
    }
    
    public function done(int $id) {
        $task = Task::find($id);
        $task->update(['status' => 'done']);

        return redirect()->route('tasks.index')->with('success', 'Task Marked as done');
    }

    public function pending(int $id) {
        $task = Task::find($id);
        $task->update(['status' => 'pending']);

        return redirect()->route('tasks.index')->with('success', 'Task Marked as Pending');
    }

    public function destroy(Task $task){
        
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task Delete Successfully');
    }
}
