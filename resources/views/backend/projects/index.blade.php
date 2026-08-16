@extends('backend.layouts.admin')

@section('title', 'Projects')

@section('page_title', 'Projects')

@section('content')

{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>
        <h1>Projects</h1>

        <p>
            Manage all interior projects from here.
        </p>
    </div>

    <a
        href="{{ route('admin.projects.create') }}"
        class="primary-btn"
    >
        + Add Project
    </a>

</div>


{{-- =====================================================
    PROJECT TABLE
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>
            <h2>All Projects</h2>

            <p>
                List of all interior projects.
            </p>
        </div>

    </div>


    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>PROJECT</th>

                    <th>CLIENT</th>

                    <th>LOCATION</th>

                    <th>START DATE</th>

                    <th>END DATE</th>

                    <th>STATUS</th>

                    <th>ACTION</th>

                </tr>

            </thead>


            <tbody>

                @forelse($projects as $project)

                    <tr>

                        {{-- Project --}}
                        <td>

                            <div class="project-name">
                                {{ $project->project_name }}
                            </div>

                            <small>
                                Project #{{ $loop->iteration }}
                            </small>

                        </td>


                        {{-- Client --}}
                        <td>

                            @if($project->client)

                                {{ $project->client->name }}

                            @else

                                <span class="text-muted">
                                    No Client
                                </span>

                            @endif

                        </td>


                        {{-- Location --}}
                        <td>
                            {{ $project->location ?? 'N/A' }}
                        </td>


                        {{-- Start Date --}}
                        <td>

                            {{ $project->start_date
                                ? $project->start_date->format('d M Y')
                                : 'N/A'
                            }}

                        </td>


                        {{-- End Date --}}
                        <td>

                            {{ $project->end_date
                                ? $project->end_date->format('d M Y')
                                : 'N/A'
                            }}

                        </td>


                        {{-- Status --}}
                        <td>

                            <span class="status {{ $project->status_badge }}">

                                {{ ucfirst($project->status) }}

                            </span>

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="table-actions">

                                {{-- View --}}
                                <a
                                    href="{{ route('admin.projects.show', $project) }}"
                                    class="small-action view"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.projects.edit', $project) }}"
                                    class="small-action edit"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('admin.projects.destroy', $project) }}"
                                    method="POST"
                                    class="delete-form"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="small-action delete"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    {{-- Empty State --}}
                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            <div class="empty-icon">
                                ▣
                            </div>

                            <h3>
                                No Projects Found
                            </h3>

                            <p>
                                You haven't added any projects yet.
                            </p>

                            <a
                                href="{{ route('admin.projects.create') }}"
                                class="primary-btn"
                            >
                                + Add First Project
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection