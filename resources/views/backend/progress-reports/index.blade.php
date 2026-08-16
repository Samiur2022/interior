@extends('backend.layouts.admin')

@section('title', 'Progress Reports')

@section('page_title', 'Progress Reports')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Progress Reports
        </h1>

        <p>
            Track overall project progress and work-wise updates.
        </p>

    </div>


    <a
        href="{{ route('admin.progress-reports.create') }}"
        class="primary-btn"
    >
        + Add Progress
    </a>

</div>



{{-- =====================================================
    PROJECT PROGRESS LIST
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Progress List
            </h2>

            <p>
                Each project is shown once with its overall progress.
            </p>

        </div>


        <span class="table-count">

            {{ $projects->count() }}

            {{ $projects->count() === 1
                ? 'Project'
                : 'Projects'
            }}

        </span>

    </div>



    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        PROJECT
                    </th>

                    <th>
                        OVERALL PROGRESS
                    </th>

                    <th>
                        STATUS
                    </th>

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>



            <tbody>

                @forelse($projects as $project)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | OVERALL PROGRESS
                        |--------------------------------------------------------------------------
                        */

                        $overallProgress =
                            $project->progressReports
                                ->sum('progress_percent');


                        $overallProgress =
                            min(
                                $overallProgress,
                                100
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | PROGRESS CLASS
                        |--------------------------------------------------------------------------
                        */

                        if ($overallProgress >= 100) {

                            $progressClass =
                                'progress-completed';

                        } elseif ($overallProgress >= 70) {

                            $progressClass =
                                'progress-high';

                        } elseif ($overallProgress >= 40) {

                            $progressClass =
                                'progress-medium';

                        } else {

                            $progressClass =
                                'progress-low';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS CLASS
                        |--------------------------------------------------------------------------
                        */

                        $statusClass =
                            match($project->status) {

                                'pending' =>
                                    'status-warning',

                                'ongoing' =>
                                    'status-info',

                                'on-hold' =>
                                    'status-danger',

                                'completed' =>
                                    'status-success',

                                'cancelled' =>
                                    'status-danger',

                                default =>
                                    'status-secondary',

                            };

                    @endphp



                    <tr>


                        {{-- =================================================
                            SERIAL
                        ================================================== --}}

                        <td>
                            {{ $loop->iteration }}
                        </td>



                        {{-- =================================================
                            PROJECT
                        ================================================== --}}

                        <td>

                            <strong>
                                {{ $project->project_name }}
                            </strong>

                        </td>



                        {{-- =================================================
                            OVERALL PROGRESS
                        ================================================== --}}

                        <td>

                            <div class="progress-wrapper">

                                <div class="progress-info">

                                    <strong>
                                        {{ $overallProgress }}%
                                    </strong>

                                </div>


                                <div class="progress-bar">

                                    <div
                                        class="progress-bar-fill {{ $progressClass }}"
                                        style="
                                            width: {{ $overallProgress }}%;
                                        "
                                    ></div>

                                </div>

                            </div>

                        </td>



                        {{-- =================================================
                            PROJECT STATUS
                        ================================================== --}}

                        <td>

                            <span
                                class="status-badge {{ $statusClass }}"
                            >

                                {{ ucfirst(
                                    str_replace(
                                        '-',
                                        ' ',
                                        $project->status
                                    )
                                ) }}

                            </span>

                        </td>



                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}

                        <td>

                            <div class="table-actions">

                                @if(
                                    $project->progressReports->isNotEmpty()
                                )

                                    <a
                                        href="{{ route(
                                            'admin.progress-reports.show',
                                            $project->progressReports->first()
                                        ) }}"
                                        class="small-action view"
                                    >
                                        View
                                    </a>

                                @else

                                    <a
                                        href="{{ route(
                                            'admin.progress-reports.create'
                                        ) }}"
                                        class="small-action edit"
                                    >
                                        Add
                                    </a>

                                @endif

                            </div>

                        </td>


                    </tr>

                @empty


                    {{-- =================================================
                        EMPTY STATE
                    ================================================== --}}

                    <tr>

                        <td
                            colspan="5"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No projects found.
                                </strong>

                                <p>
                                    Add a project progress report
                                    to start tracking progress.
                                </p>

                                <a
                                    href="{{ route(
                                        'admin.progress-reports.create'
                                    ) }}"
                                    class="primary-btn"
                                >
                                    + Add Progress
                                </a>

                            </div>

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>

    </div>

</div>


@endsection