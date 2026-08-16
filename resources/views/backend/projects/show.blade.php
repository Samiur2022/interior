@extends('backend.layouts.admin')

@section('title', 'Project Details')

@section('page_title', 'Project Details')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Project Details
        </h1>

        <p>
            View complete information about this project.
        </p>

    </div>


    <div class="page-header-actions">

        {{-- Edit Project --}}
        <a
            href="{{ route('admin.projects.edit', $project) }}"
            class="primary-btn"
        >
            Edit Project
        </a>


        {{-- Back --}}
        <a
            href="{{ route('admin.projects.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>

    </div>

</div>



{{-- =====================================================
    PROJECT INFORMATION
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Information
            </h2>

            <p>
                Basic information about the project.
            </p>

        </div>


        {{-- Project Status --}}
        <span class="status {{ $project->status_badge }}">

            {{ ucfirst($project->status) }}

        </span>

    </div>


    <div class="details-grid">


        {{-- Project Name --}}
        <div class="detail-item">

            <span class="detail-label">
                Project Name
            </span>

            <strong class="detail-value">
                {{ $project->project_name }}
            </strong>

        </div>



        {{-- Client --}}
        <div class="detail-item">

            <span class="detail-label">
                Client
            </span>

            <strong class="detail-value">

                @if($project->client)

                    {{ $project->client->name }}

                @else

                    <span class="text-muted">
                        No Client
                    </span>

                @endif

            </strong>

        </div>



        {{-- Location --}}
        <div class="detail-item">

            <span class="detail-label">
                Location
            </span>

            <strong class="detail-value">

                {{ $project->location ?? 'N/A' }}

            </strong>

        </div>



        {{-- Start Date --}}
        <div class="detail-item">

            <span class="detail-label">
                Start Date
            </span>

            <strong class="detail-value">

                {{ $project->start_date
                    ? $project->start_date->format('d M Y')
                    : 'N/A'
                }}

            </strong>

        </div>



        {{-- End Date --}}
        <div class="detail-item">

            <span class="detail-label">
                End Date
            </span>

            <strong class="detail-value">

                {{ $project->end_date
                    ? $project->end_date->format('d M Y')
                    : 'N/A'
                }}

            </strong>

        </div>



        {{-- Project ID --}}
        <div class="detail-item">

            <span class="detail-label">
                Project ID
            </span>

            <strong class="detail-value">
                #{{ $project->id }}
            </strong>

        </div>


    </div>

</div>



{{-- =====================================================
    FUTURE PROJECT MODULES
====================================================== --}}

<div class="dashboard-grid">


    {{-- Budget --}}
    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Budget
                </h2>

                <p>
                    Project budget information.
                </p>

            </div>

        </div>


        <div class="empty-module">

            <span>
                Budget module will be available here.
            </span>

        </div>

    </div>



    {{-- Payments --}}
    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Payments
                </h2>

                <p>
                    Project payment information.
                </p>

            </div>

        </div>


        <div class="empty-module">

            <span>
                Payment module will be available here.
            </span>

        </div>

    </div>


</div>



<div class="dashboard-grid">


    {{-- Materials --}}
    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Materials
                </h2>

                <p>
                    Materials used in this project.
                </p>

            </div>

        </div>


        <div class="empty-module">

            <span>
                Project materials will be available here.
            </span>

        </div>

    </div>



    {{-- Progress --}}
    <div class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Progress
                </h2>

                <p>
                    Project progress information.
                </p>

            </div>

        </div>


        <div class="empty-module">

            <span>
                Progress reports will be available here.
            </span>

        </div>

    </div>


</div>


@endsection