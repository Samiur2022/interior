@extends('backend.layouts.admin')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('content')


{{-- =====================================================
    DASHBOARD HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Dashboard
        </h1>

        <p>
            Overview of your interior project management system.
        </p>

    </div>

</div>



{{-- =====================================================
    SUMMARY CARDS
====================================================== --}}

<div class="dashboard-cards">


    {{-- Total Clients --}}
    <div class="summary-card">

        <div class="summary-card-content">

            <span class="summary-label">
                Total Clients
            </span>

            <strong class="summary-number">
                {{ $totalClients }}
            </strong>

        </div>

        <div class="summary-icon">
            C
        </div>

    </div>



    {{-- Total Projects --}}
    <div class="summary-card">

        <div class="summary-card-content">

            <span class="summary-label">
                Total Projects
            </span>

            <strong class="summary-number">
                {{ $totalProjects }}
            </strong>

        </div>

        <div class="summary-icon">
            P
        </div>

    </div>



    {{-- Ongoing Projects --}}
    <div class="summary-card">

        <div class="summary-card-content">

            <span class="summary-label">
                Ongoing Projects
            </span>

            <strong class="summary-number">
                {{ $ongoingProjects }}
            </strong>

        </div>

        <div class="summary-icon">
            O
        </div>

    </div>



    {{-- Completed Projects --}}
    <div class="summary-card">

        <div class="summary-card-content">

            <span class="summary-label">
                Completed Projects
            </span>

            <strong class="summary-number">
                {{ $completedProjects }}
            </strong>

        </div>

        <div class="summary-icon">
            ✓
        </div>

    </div>


</div>



{{-- =====================================================
    PROJECT STATUS
====================================================== --}}

<div class="dashboard-grid">


    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Project Status
                </h2>

                <p>
                    Current project overview.
                </p>

            </div>

        </div>


        <div class="status-summary">


            <div class="status-summary-item">

                <span>
                    Pending
                </span>

                <strong>
                    {{ $pendingProjects }}
                </strong>

            </div>


            <div class="status-summary-item">

                <span>
                    Ongoing
                </span>

                <strong>
                    {{ $ongoingProjects }}
                </strong>

            </div>


            <div class="status-summary-item">

                <span>
                    Completed
                </span>

                <strong>
                    {{ $completedProjects }}
                </strong>

            </div>


            <div class="status-summary-item">

                <span>
                    On Hold
                </span>

                <strong>
                    {{ $onHoldProjects }}
                </strong>

            </div>


        </div>

    </div>



    {{-- Quick Actions --}}

    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Quick Actions
                </h2>

                <p>
                    Frequently used actions.
                </p>

            </div>

        </div>


        <div class="quick-actions">


            <a
                href="{{ route('admin.clients.create') }}"
                class="quick-action"
            >
                + Add Client
            </a>


            <a
                href="{{ route('admin.projects.create') }}"
                class="quick-action"
            >
                + Add Project
            </a>


        </div>

    </div>


</div>



{{-- =====================================================
    RECENT PROJECTS
====================================================== --}}

<div class="panel">


    <div class="panel-header">

        <div>

            <h2>
                Recent Projects
            </h2>

            <p>
                Latest projects added to the system.
            </p>

        </div>


        <a
            href="{{ route('admin.projects.index') }}"
            class="secondary-btn"
        >
            View All
        </a>

    </div>



    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>
                        PROJECT
                    </th>

                    <th>
                        CLIENT
                    </th>

                    <th>
                        STATUS
                    </th>

                    <th>
                        START DATE
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($recentProjects as $project)

                    <tr>

                        <td>

                            <strong>
                                {{ $project->project_name }}
                            </strong>

                        </td>


                        <td>

                            {{ $project->client->name ?? 'No Client' }}

                        </td>


                        <td>

                            <span
                                class="status {{ $project->status_badge }}"
                            >
                                {{ ucfirst($project->status) }}
                            </span>

                        </td>


                        <td>

                            {{ $project->start_date
                                ? $project->start_date->format('d M Y')
                                : 'N/A'
                            }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="empty-state"
                        >

                            No projects available yet.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection