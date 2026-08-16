@extends('backend.layouts.admin')

@section('title', 'Budget Details')

@section('page_title', 'Budget Details')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Budget Details
        </h1>

        <p>
            Financial summary for this project.
        </p>

    </div>


    <div class="table-actions">

        <a
            href="{{ route('admin.budgets.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>


        @if(
            $budget->project &&
            $budget->project->status !== 'cancelled'
        )

            <a
                href="{{ route(
                    'admin.budgets.edit',
                    $budget
                ) }}"
                class="primary-btn"
            >
                Edit Budget
            </a>

        @endif

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
                Basic information related to this budget.
            </p>

        </div>

    </div>


    <div class="detail-grid">


        {{-- =================================================
            PROJECT NAME
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Project
            </span>

            <strong class="detail-value">

                {{ $budget->project->project_name ?? 'N/A' }}

            </strong>

        </div>



        {{-- =================================================
            CLIENT
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Client
            </span>

            <strong class="detail-value">

                {{ $budget->project->client->name ?? 'N/A' }}

            </strong>

        </div>



        {{-- =================================================
            PROJECT STATUS
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Project Status
            </span>


            @php

                $projectStatus =
                    $budget->project->status
                    ?? null;


                $projectStatusClass = match(
                    $projectStatus
                ) {

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


            <span
                class="status-badge {{ $projectStatusClass }}"
            >

                {{ $projectStatus
                    ? ucfirst(
                        str_replace(
                            '-',
                            ' ',
                            $projectStatus
                        )
                    )
                    : 'N/A'
                }}

            </span>

        </div>



        {{-- =================================================
            BUDGET CREATED
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Budget Created
            </span>

            <strong class="detail-value">

                {{ $budget->created_at
                    ? $budget->created_at->format('d M Y')
                    : 'N/A'
                }}

            </strong>

        </div>

    </div>

</div>



{{-- =====================================================
    FINANCIAL SUMMARY
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Financial Summary
            </h2>

            <p>
                Estimated cost compared with actual cost.
            </p>

        </div>

    </div>



    <div class="budget-detail-grid">


        {{-- =================================================
            ESTIMATED COST
        ================================================== --}}

        <div class="budget-detail-card">

            <span>
                Estimated Cost
            </span>

            <strong>

                ৳{{ number_format(
                    (float) $budget->estimated_cost,
                    2
                ) }}

            </strong>

        </div>



        {{-- =================================================
            ACTUAL COST
        ================================================== --}}

        <div class="budget-detail-card">

            <span>
                Actual Cost
            </span>

            <strong>

                @if($budget->actual_cost !== null)

                    ৳{{ number_format(
                        (float) $budget->actual_cost,
                        2
                    ) }}

                @else

                    Not Set

                @endif

            </strong>

        </div>



        {{-- =================================================
            VARIANCE
        ================================================== --}}

        @php

            $variance =
                (float) $variance;

            $varianceClass =
                $variance > 0
                    ? 'text-success'
                    : (
                        $variance < 0
                            ? 'text-danger'
                            : 'text-muted'
                    );

        @endphp


        <div class="budget-detail-card">

            <span>
                Variance
            </span>

            <strong class="{{ $varianceClass }}">

                @if($variance > 0)

                    +৳{{ number_format(
                        abs($variance),
                        2
                    ) }}

                @elseif($variance < 0)

                    -৳{{ number_format(
                        abs($variance),
                        2
                    ) }}

                @else

                    ৳0.00

                @endif

            </strong>

        </div>



        {{-- =================================================
            BUDGET STATUS
        ================================================== --}}

        @php

            $varianceStatusClass = match(
                $varianceStatus
            ) {

                'Under Budget' =>
                    'status-success',

                'Over Budget' =>
                    'status-danger',

                'On Budget' =>
                    'status-info',

                default =>
                    'status-secondary',

            };

        @endphp


        <div class="budget-detail-card">

            <span>
                Budget Status
            </span>

            <strong>

                <span
                    class="status-badge {{ $varianceStatusClass }}"
                >

                    {{ $varianceStatus }}

                </span>

            </strong>

        </div>

    </div>

</div>



{{-- =====================================================
    BUDGET ANALYSIS
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Budget Analysis
            </h2>

            <p>
                Current budget performance.
            </p>

        </div>

    </div>


    <div class="budget-analysis">


        @if($variance > 0)

            <div class="alert alert-success">

                <div>

                    <strong>
                        Project is Under Budget
                    </strong>

                    <p>
                        The current actual cost is
                        ৳{{ number_format(
                            abs($variance),
                            2
                        ) }}
                        below the estimated budget.
                    </p>

                </div>

            </div>


        @elseif($variance < 0)

            <div class="alert alert-error">

                <div>

                    <strong>
                        Project is Over Budget
                    </strong>

                    <p>
                        The current actual cost exceeds
                        the estimated budget by
                        ৳{{ number_format(
                            abs($variance),
                            2
                        ) }}.
                    </p>

                </div>

            </div>


        @else

            <div class="alert alert-success">

                <div>

                    <strong>
                        Project is On Budget
                    </strong>

                    <p>
                        The actual cost matches the
                        estimated budget.
                    </p>

                </div>

            </div>

        @endif

    </div>

</div>



{{-- =====================================================
    ACTIONS
====================================================== --}}

<div class="form-actions">


    <a
        href="{{ route('admin.budgets.index') }}"
        class="secondary-btn"
    >
        ← Back to Budgets
    </a>


    @if(
        $budget->project &&
        $budget->project->status !== 'cancelled'
    )

        <a
            href="{{ route(
                'admin.budgets.edit',
                $budget
            ) }}"
            class="primary-btn"
        >
            Edit Budget
        </a>

    @endif

</div>


@endsection