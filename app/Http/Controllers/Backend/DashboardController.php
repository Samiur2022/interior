<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Client Summary
        |--------------------------------------------------------------------------
        */

        $totalClients = Client::count();


        /*
        |--------------------------------------------------------------------------
        | Project Summary
        |--------------------------------------------------------------------------
        */

        $totalProjects = Project::count();

        $pendingProjects = Project::where('status', 'pending')->count();

        $ongoingProjects = Project::where('status', 'ongoing')->count();

        $completedProjects = Project::where('status', 'completed')->count();

        $onHoldProjects = Project::where('status', 'on-hold')->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Projects
        |--------------------------------------------------------------------------
        |
        | Get the latest 5 projects with their clients.
        |
        */

        $recentProjects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Send Data To Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('backend.dashboard', compact(
            'totalClients',
            'totalProjects',
            'pendingProjects',
            'ongoingProjects',
            'completedProjects',
            'onHoldProjects',
            'recentProjects'
        ));
    }
}