@extends('backend.layouts.admin')

@section('title', 'Project Reports')

@section('page_title', 'Project Reports')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Project Reports
        </h1>

        <p>
            Monitor project progress, budget, payments and materials.
        </p>

    </div>


    <div class="table-actions">

        <button
            type="button"
            class="primary-btn"
            onclick="printFilteredReport()"
        >
            🖨 Print Report
        </button>

    </div>

</div>



{{-- =====================================================
    DATE FILTER
====================================================== --}}

<div class="panel report-filter-panel">

    <div class="panel-header">

        <div>

            <h2>
                Report Filter
            </h2>

            <p>
                Filter projects by project date range.
            </p>

        </div>

    </div>



    <form
        action="{{ route('admin.reports.index') }}"
        method="GET"
        id="reportFilterForm"
    >

        <div class="report-filter-grid">


            {{-- FROM DATE --}}

            <div class="form-group">

                <label for="from_date">
                    From Date
                </label>

                <input
                    type="date"
                    name="from_date"
                    id="from_date"
                    value="{{ $fromDate }}"
                >

                @error('from_date')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- TO DATE --}}

            <div class="form-group">

                <label for="to_date">
                    To Date
                </label>

                <input
                    type="date"
                    name="to_date"
                    id="to_date"
                    value="{{ $toDate }}"
                >

                @error('to_date')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- ACTIONS --}}

            <div class="report-filter-actions">

                <button
                    type="submit"
                    class="primary-btn"
                >
                    Apply Filter
                </button>


                <a
                    href="{{ route('admin.reports.index') }}"
                    class="secondary-btn"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>



{{-- =====================================================
    DATE RANGE DISPLAY
====================================================== --}}

@if($fromDate || $toDate)

    <div class="report-filter-result">

        <strong>
            Report Period:
        </strong>


        @if($fromDate && $toDate)

            {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}

            <span>
                →
            </span>

            {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}

        @elseif($fromDate)

            From
            {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}

        @elseif($toDate)

            Up to
            {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}

        @endif

    </div>

@endif



{{-- =====================================================
    SUMMARY CARDS
====================================================== --}}

<div class="report-summary-grid">


    {{-- PROJECTS --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Total Projects
        </span>

        <strong class="report-summary-value">
            {{ $projectCount }}
        </strong>

    </div>



    {{-- BUDGET --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Estimated Budget
        </span>

        <strong class="report-summary-value">

            ৳{{ number_format(
                $totalBudget,
                2
            ) }}

        </strong>

    </div>



    {{-- MATERIAL COST --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Material Cost
        </span>

        <strong class="report-summary-value">

            ৳{{ number_format(
                $totalMaterialCost,
                2
            ) }}

        </strong>

    </div>



    {{-- TOTAL PAID --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Total Paid
        </span>

        <strong class="report-summary-value">

            ৳{{ number_format(
                $totalPaid,
                2
            ) }}

        </strong>

    </div>



    {{-- REMAINING --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Remaining Payment
        </span>

        <strong class="report-summary-value">

            ৳{{ number_format(
                $totalRemaining,
                2
            ) }}

        </strong>

    </div>



    {{-- AVERAGE PROGRESS --}}

    <div class="report-summary-card">

        <span class="report-summary-label">
            Average Progress
        </span>

        <strong class="report-summary-value">

            {{ $averageProgress }}%

        </strong>

    </div>

</div>



{{-- =====================================================
    PROJECT REPORT TABLE
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Report
            </h2>

            <p>
                Project-wise financial and progress summary.
            </p>

        </div>


        <span class="table-count">

            {{ $projectCount }}

            {{ $projectCount === 1
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
                        CLIENT
                    </th>

                    <th>
                        DATE
                    </th>

                    <th>
                        BUDGET
                    </th>

                    <th>
                        PAID
                    </th>

                    <th>
                        MATERIAL
                    </th>

                    <th>
                        PROGRESS
                    </th>

                    <th>
                        STATUS
                    </th>

                    <th>
                        ACTION
                    </th>

                </tr>

            </thead>



            <tbody>

                @forelse($projects as $project)

                    @php

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


                        /*
                        |--------------------------------------------------------------------------
                        | PROGRESS
                        |--------------------------------------------------------------------------
                        */

                        $progress =
                            min(
                                (float)
                                $project->overall_progress,
                                100
                            );


                        if ($progress >= 100) {

                            $progressClass =
                                'progress-completed';

                        } elseif ($progress >= 70) {

                            $progressClass =
                                'progress-high';

                        } elseif ($progress >= 40) {

                            $progressClass =
                                'progress-medium';

                        } else {

                            $progressClass =
                                'progress-low';

                        }

                    @endphp



                    <tr>


                        {{-- SERIAL --}}

                        <td>
                            {{ $loop->iteration }}
                        </td>



                        {{-- PROJECT --}}

                        <td>

                            <strong>
                                {{ $project->project_name }}
                            </strong>

                        </td>



                        {{-- CLIENT --}}

                        <td>

                            @if($project->client)

                                {{ $project->client->name }}

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>



                        {{-- DATE --}}

                        <td>

                            <div class="report-date">

                                <span>

                                    {{ \Carbon\Carbon::parse(
                                        $project->start_date
                                    )->format('d M Y') }}

                                </span>


                                <span class="date-arrow">
                                    →
                                </span>


                                <span>

                                    @if($project->end_date)

                                        {{ \Carbon\Carbon::parse(
                                            $project->end_date
                                        )->format('d M Y') }}

                                    @else

                                        Ongoing

                                    @endif

                                </span>

                            </div>

                        </td>



                        {{-- BUDGET --}}

                        <td>

                            ৳{{ number_format(
                                $project->estimated_budget,
                                2
                            ) }}

                        </td>



                        {{-- PAID --}}

                        <td>

                            ৳{{ number_format(
                                $project->total_paid,
                                2
                            ) }}

                        </td>



                        {{-- MATERIAL --}}

                        <td>

                            ৳{{ number_format(
                                $project->material_cost,
                                2
                            ) }}

                        </td>



                        {{-- PROGRESS --}}

                        <td>

                            <div class="report-progress">

                                <div class="report-progress-header">

                                    <strong>
                                        {{ $progress }}%
                                    </strong>

                                </div>


                                <div class="progress-bar">

                                    <div
                                        class="
                                            progress-bar-fill
                                            {{ $progressClass }}
                                        "
                                        style="
                                            width: {{ $progress }}%;
                                        "
                                    ></div>

                                </div>

                            </div>

                        </td>



                        {{-- STATUS --}}

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



                        {{-- ACTION --}}

                        <td>

                            <a
                                href="{{ route(
                                    'admin.reports.show',
                                    $project
                                ) }}"
                                class="small-action view"
                            >
                                View
                            </a>

                        </td>


                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="10"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No projects found.
                                </strong>

                                <p>
                                    No projects match the selected
                                    date range.
                                </p>


                                <a
                                    href="{{ route(
                                        'admin.reports.index'
                                    ) }}"
                                    class="secondary-btn"
                                >
                                    Clear Filter
                                </a>

                            </div>

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
    PRINT FORM
====================================================== --}}

<form
    method="GET"
    action="{{ route('admin.reports.print') }}"
    id="printReportForm"
    target="_blank"
    style="display:none;"
>

    <input
        type="hidden"
        name="from_date"
        id="print_from_date"
        value="{{ $fromDate }}"
    >

    <input
        type="hidden"
        name="to_date"
        id="print_to_date"
        value="{{ $toDate }}"
    >

</form>



{{-- =====================================================
    JAVASCRIPT
====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | DATE INPUTS
        |--------------------------------------------------------------------------
        */

        const fromDate =
            document.getElementById(
                'from_date'
            );

        const toDate =
            document.getElementById(
                'to_date'
            );


        /*
        |--------------------------------------------------------------------------
        | CLIENT-SIDE DATE VALIDATION
        |--------------------------------------------------------------------------
        |
        | Same date is VALID.
        |
        */

        function validateDateRange() {

            if (
                !fromDate.value ||
                !toDate.value
            ) {

                return true;

            }


            const from =
                new Date(
                    fromDate.value
                );

            const to =
                new Date(
                    toDate.value
                );


            /*
            | From > To
            */

            if (from > to) {

                toDate.setCustomValidity(
                    'To Date must be the same as or later than From Date.'
                );

                return false;

            }


            /*
            | Same date is valid.
            */

            toDate.setCustomValidity('');

            return true;

        }


        fromDate.addEventListener(
            'change',
            validateDateRange
        );


        toDate.addEventListener(
            'change',
            validateDateRange
        );


        document
            .getElementById('reportFilterForm')
            .addEventListener(
                'submit',
                function (event) {

                    if (!validateDateRange()) {

                        event.preventDefault();

                        toDate.reportValidity();

                    }

                }
            );

    }
);



/*
|--------------------------------------------------------------------------
| PRINT FILTERED REPORT
|--------------------------------------------------------------------------
*/

function printFilteredReport() {

    const fromDate =
        document.getElementById(
            'from_date'
        ).value;


    const toDate =
        document.getElementById(
            'to_date'
        ).value;


    /*
    |--------------------------------------------------------------------------
    | DATE VALIDATION
    |--------------------------------------------------------------------------
    */

    if (fromDate && toDate) {

        const from =
            new Date(fromDate);

        const to =
            new Date(toDate);


        if (from > to) {

            alert(
                'To Date must be the same as or later than From Date.'
            );

            return;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SET PRINT VALUES
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'print_from_date'
    ).value = fromDate;


    document.getElementById(
        'print_to_date'
    ).value = toDate;


    /*
    |--------------------------------------------------------------------------
    | OPEN PRINT PAGE
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            'printReportForm'
        )
        .submit();

}

</script>


@endsection