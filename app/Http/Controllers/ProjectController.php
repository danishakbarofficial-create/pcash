<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  public function index()
{
    $projects = Project::latest()->get();
    
    // Sirf wo users jo manager hain (agar aap Role system use kar rahe hain)
    // $managers = \App\Models\User::role('manager')->get(); 
    
    // Agar simple role column hai toh:
    $managers = \App\Models\User::where('role', 'manager')->get(); 

    return view('admin.projects.index', compact('projects', 'managers'));
}

    public function store(Request $request)
    {
        // Validation mein naye fields add kar diye hain
        $request->validate([
            'name' => 'required|unique:projects,name',
            'location' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
        ]);

        // Sab data aik saath create karein
        Project::create([
            'name' => $request->name,
            'location' => $request->location,
            'manager_name' => $request->manager_name,
        ]);

        return back()->with('success', 'Project Added Successfully!');
    }

    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return back()->with('success', 'Project Deleted!');
    }
}