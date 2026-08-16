@extends('backend.layouts.admin')

@section('title', 'Project Progress Details')

@section('page_title', 'Project Progress Details')

@section('content')

{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Project Progress
        </h1>

        <p>
            View progress details for this project.
        </p>

    </div>


    <div class="table-actions">

        <a
            href="{{ route('admin.progress-reports.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>


        @if(in_array($project->status, ['pending', 'ongoing']))

            <a
                href="{{ route('admin.progress-reports.create') }}"
                class="primary-btn"
            >
                + Add Progress
            </a>

        @endif

    </div>

</div>



{{-- =====================================================
    PROJECT SUMMARY
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                {{ $project->project_name }}
            </h2>

            <p>
                Overall project progress summary.
            </p>

        </div>


        <span class="status-badge status-info">

            {{ $overallProgress }}%

        </span>

    </div>



    <div class="detail-grid">


        {{-- PROJECT --}}

        <div class="detail-item">

            <span class="detail-label">
                Project
            </span>

            <strong class="detail-value">

                {{ $project->project_name }}

            </strong>

        </div>



        {{-- STATUS --}}

        <div class="detail-item">

            <span class="detail-label">
                Project Status
            </span>

            @php

                $statusClass = match($project->status) {

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


            <strong class="detail-value">

                <span class="status-badge {{ $statusClass }}">

                    {{ ucfirst(
                        str_replace(
                            '-',
                            ' ',
                            $project->status
                        )
                    ) }}

                </span>

            </strong>

        </div>



        {{-- START DATE --}}

        <div class="detail-item">

            <span class="detail-label">
                Start Date
            </span>

            <strong class="detail-value">

                @if($project->start_date)

                    {{ \Carbon\Carbon::parse(
                        $project->start_date
                    )->format('d M Y') }}

                @else

                    N/A

                @endif

            </strong>

        </div>



        {{-- END DATE --}}

        <div class="detail-item">

            <span class="detail-label">
                End Date
            </span>

            <strong class="detail-value">

                @if($project->end_date)

                    {{ \Carbon\Carbon::parse(
                        $project->end_date
                    )->format('d M Y') }}

                @else

                    N/A

                @endif

            </strong>

        </div>

    </div>

</div>



{{-- =====================================================
    OVERALL PROGRESS
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Overall Progress
            </h2>

            <p>
                Total progress of all work types.
            </p>

        </div>


        <strong class="overall-progress-value">

            {{ $overallProgress }}%

        </strong>

    </div>



    <div class="progress-detail-card">

        <div class="progress-detail-bar">

            <div
                class="progress-detail-fill"
                style="
                    width: {{ min(
                        $overallProgress,
                        100
                    ) }}%;
                "
            ></div>

        </div>


        <div class="progress-detail-status">

            @if($overallProgress >= 100)

                Project Completed

            @elseif($overallProgress > 0)

                Project In Progress

            @else

                No Progress Yet

            @endif

        </div>

    </div>

</div>



{{-- =====================================================
    WORK PROGRESS
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Work Progress
            </h2>

            <p>
                Progress breakdown by work type.
            </p>

        </div>


        <span class="table-count">

            {{ $project->progressReports->count() }}

            {{ $project->progressReports->count() === 1
                ? 'Work'
                : 'Works'
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
                        WORK TYPE
                    </th>

                    <th>
                        PROGRESS
                    </th>

                    <th>
                        DESCRIPTION
                    </th>

                    <th>
                        IMAGE
                    </th>

                    <th>
                        UPDATED
                    </th>

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse(
                    $project->progressReports
                    as $progressReport
                )

                    @php

                        $progress =
                            (int) $progressReport
                                ->progress_percent;

                    @endphp


                    <tr>


                        {{-- SERIAL --}}

                        <td>
                            {{ $loop->iteration }}
                        </td>



                        {{-- WORK TYPE --}}

                        <td>

                            <strong>
                                {{ $progressReport->work_type }}
                            </strong>

                        </td>



                        {{-- PROGRESS --}}

                        <td>

                            <div class="progress-wrapper">

                                <div class="progress-info">

                                    <strong>
                                        {{ $progress }}%
                                    </strong>

                                </div>


                                <div class="progress-bar">

                                    <div
                                        class="
                                            progress-bar-fill

                                            @if($progress >= 100)
                                                progress-completed
                                            @elseif($progress >= 70)
                                                progress-high
                                            @elseif($progress >= 40)
                                                progress-medium
                                            @else
                                                progress-low
                                            @endif
                                        "
                                        style="
                                            width: {{ min(
                                                $progress,
                                                100
                                            ) }}%;
                                        "
                                    ></div>

                                </div>

                            </div>

                        </td>



                        {{-- DESCRIPTION --}}

                        <td>

                            @if($progressReport->description)

                                <span
                                    class="progress-description"
                                    title="{{ $progressReport->description }}"
                                >

                                    {{ \Illuminate\Support\Str::limit(
                                        $progressReport->description,
                                        45
                                    ) }}

                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>



                        {{-- IMAGE --}}

                        <td>

                            @if($progressReport->image)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $progressReport->image
                                    ) }}"
                                    alt="{{ $progressReport->work_type }}"
                                    class="progress-thumbnail"
                                >

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>



                        {{-- UPDATED --}}

                        <td>

                            {{ $progressReport->updated_at
                                ? $progressReport->updated_at
                                    ->format('d M Y')
                                : 'N/A'
                            }}

                        </td>



                        {{-- ACTIONS --}}

                        <td>

                            <div class="table-actions">


                                {{-- EDIT --}}

                                @if(
                                    in_array(
                                        $project->status,
                                        ['pending', 'ongoing']
                                    )
                                )

                                    <a
                                        href="{{ route(
                                            'admin.progress-reports.edit',
                                            $progressReport
                                        ) }}"
                                        class="small-action edit"
                                    >
                                        Edit
                                    </a>

                                @else

                                    <span class="text-muted">
                                        Locked
                                    </span>

                                @endif


                                {{-- DELETE --}}

                                @if(
                                    in_array(
                                        $project->status,
                                        ['pending', 'ongoing']
                                    )
                                )

                                    <form
                                        action="{{ route(
                                            'admin.progress-reports.destroy',
                                            $progressReport
                                        ) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="
                                            return confirm(
                                                'Are you sure you want to delete this progress?'
                                            );
                                        "
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

                                @endif

                            </div>

                        </td>


                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No progress found.
                                </strong>

                                <p>
                                    No work progress has been added
                                    to this project yet.
                                </p>


                                @if(
                                    in_array(
                                        $project->status,
                                        ['pending', 'ongoing']
                                    )
                                )

                                    <a
                                        href="{{ route(
                                            'admin.progress-reports.create'
                                        ) }}"
                                        class="primary-btn"
                                    >
                                        + Add Progress
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
    FOOTER
====================================================== --}}

<div class="form-actions">

    <a
        href="{{ route(
            'admin.progress-reports.index'
        ) }}"
        class="secondary-btn"
    >
        ← Back to Progress Reports
    </a>


    @if(
        in_array(
            $project->status,
            ['pending', 'ongoing']
        )
    )

        <a
            href="{{ route(
                'admin.progress-reports.create'
            ) }}"
            class="primary-btn"
        >
            + Add Work Progress
        </a>

    @endif

</div>


@endsection