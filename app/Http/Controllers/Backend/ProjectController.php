<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
   
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Get all projects
        |--------------------------------------------------------------------------
        |
        */

        $projects = Project::with('client')
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Send projects to the index view
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.projects.index',
            compact('projects')
        );
    }


    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Get all clients
        |--------------------------------------------------------------------------
        |
        | We need clients for the Client dropdown in the project form.
        |
        */

        $clients = Client::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Show create project form
        |--------------------------------------------------------------------------
        */

        return view('backend.projects.create', compact('clients') );
    }


    /**
     * Store a newly created project in database.
     
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate project information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Selected client must exist in clients table.
            'client_id' => 'required|exists:clients,id',

            // Project name is required.
            'project_name' => 'required|string|max:255',

            // Location is optional.
            'location' => 'nullable|string|max:255',

            // Start date is required.
            'start_date' => 'required|date',

            // End date is optional.
            'end_date' => 'nullable|date|after_or_equal:start_date',

            // Project status.
            'status' => 'required|in:pending,ongoing,completed,on-hold,cancelled',
        ]);


        /*
        |--------------------------------------------------------------------------
        |
        | auth()->id() returns the currently logged-in user's ID.
        |
        */

        $validated['user_id'] = session('admin_user_id');


        /*
        |--------------------------------------------------------------------------
        | Create project
        |--------------------------------------------------------------------------
        */

        Project::create($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect to project list
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }


    /**
     * Display a specific project.
     *
     * URL:
     * GET /admin/projects/{project}
     */
    public function show(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Load related client
        |--------------------------------------------------------------------------
        */

        $project->load('client');


        /*
        |--------------------------------------------------------------------------
        | Show project details
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.projects.show',
            compact('project')
        );
    }


    /**
     * Show the form for editing a project.
     *
     * URL:
     * GET /admin/projects/{project}/edit
     */
    public function edit(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Get clients for the client dropdown
        |--------------------------------------------------------------------------
        */

        $clients = Client::orderBy('name')->get();


        /*
        |--------------------------------------------------------------------------
        | Show edit form
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.projects.edit',
            compact('project', 'clients')
        );
    }


    /**
     * Update an existing project.
     *
     * URL:
     * PUT/PATCH /admin/projects/{project}
     */
    public function update(Request $request, Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate updated project information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'client_id' => 'required|exists:clients,id',

            'project_name' => 'required|string|max:255',

            'location' => 'nullable|string|max:255',

            'start_date' => 'required|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',

            'status' => 'required|in:pending,ongoing,completed,on-hold,cancelled',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update project
        |--------------------------------------------------------------------------
        */

        $project->update($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect to project list
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }


    /**
     * Delete a project.
     *
     * URL:
     * DELETE /admin/projects/{project}
     */
    public function destroy(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete the project
        |--------------------------------------------------------------------------
        */

        $project->delete();


        /*
        |--------------------------------------------------------------------------
        | Return to project list
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}