<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_hr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'study_type' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title_hr' => $request->title_hr,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'study_type' => $request->study_type,
        ]);

        return redirect()->route('dashboard')->with('success', 'Rad uspješno dodan.');
    }

    public function apply(Request $request, Task $task)
    {
        TaskApplication::create([
            'task_id' => $task->id,
            'student_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Prijava uspješna.');
    }

    public function applications()
    {
        $tasks = Task::where('user_id', Auth::id())->with('applications.student')->get();
        return view('tasks.applications', compact('tasks'));
    }

    public function accept(Request $request, TaskApplication $application)
    {
        if ($application->task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $existingAccepted = TaskApplication::where('task_id', $application->task_id)
            ->where('accepted', true)
            ->exists();

        if ($existingAccepted) {
            return redirect()->route('tasks.applications')->with('error', 'Projekt već ima prihvaćenog studenta.');
        }

        $application->update(['accepted' => true]);

        return redirect()->route('tasks.applications')->with('success', 'Student prihvaćen.');
    }

    public function reject(Request $request, TaskApplication $application)
    {
        if ($application->task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $application->update(['accepted' => false]);

        return redirect()->route('tasks.applications')->with('success', 'Prihvaćanje poništeno.');
    }

    public function withdraw(Task $task)
    {
        $application = $task->applications()->where('student_id', Auth::id())->first();
    
        if ($application) {
            $application->delete();
            return back()->with('success', 'Uspješno ste se odjavili s rada.');
        }
    
        return back()->with('error', 'Niste bili prijavljeni na ovaj rad.');
    }
}