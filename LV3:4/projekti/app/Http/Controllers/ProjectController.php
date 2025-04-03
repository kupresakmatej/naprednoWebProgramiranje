<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Dohvati projekte na kojima je korisnik voditelj ili član
        $projects = Project::where('leader_id', $userId)
            ->orWhereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        $completedTasks = [];

        return view('index', compact('projects', 'completedTasks'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'tasks' => 'nullable|array',
            'tasks.*' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        // Kreiranje novog projekta
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->price = $request->price;
        $project->tasks = json_encode($request->tasks);
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->leader_id = Auth::id(); // Trenutno prijavljeni korisnik je voditelj
        $project->save();
    
        // Automatski dodaj voditelja kao člana projekta
        $project->members()->attach(Auth::id());
    
        return redirect()->route('projects.index')->with('success', 'Projekt uspješno kreiran!');
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        
        $existingMemberIds = $project->members->pluck('id')->toArray();
        $users = User::where('id', '!=', $project->leader_id)
                     ->whereNotIn('id', $existingMemberIds)
                     ->get();
        $members = $project->members;
    
        return view('projects.show', compact('project', 'users', 'members'));
    }
    
    public function addMember(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
    
        if ($project->leader_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Samo voditelj može dodavati članove.');
        }
    
        $userId = $request->user_id;
        if ($project->members->contains($userId)) {
            return redirect()->back()->with('info', 'Ovaj korisnik je već član projekta.');
        }
    
        $project->members()->syncWithoutDetaching([$userId]);
    
        return redirect()->route('projects.show', $projectId)->with('success', 'Član uspješno dodan.');
    }
    
    public function removeMember($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        if ($project->leader_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Samo voditelj može ukloniti članove.');
        }

        $project->members()->detach($userId);

        return redirect()->back()->with('success', 'Član je uspješno uklonjen.');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        if ($project->leader_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Samo voditelj može uređivati projekt.');
        }
        return view('projects.edit', compact('project'));
    }
    
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        if ($project->leader_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Samo voditelj može uređivati projekt.');
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'tasks' => 'nullable|array',
            'tasks.*' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'tasks' => json_encode($request->tasks),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        return redirect()->route('projects.show', $project->id)->with('success', 'Projekt uspješno ažuriran.');
    }

    public function updateTasks(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $completedTasks = $request->input('completed_tasks', []);

        $project->completed_tasks = json_encode($completedTasks);
        $project->save();

        return redirect()->route('projects.show', $projectId);
    }
}
