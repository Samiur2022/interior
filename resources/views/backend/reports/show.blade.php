@extends('backend.layouts.admin')

@section('title', 'Project Report')

@section('page_title', 'Project Report')

@section('content')

{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Project Report
        </h1>

        <p>
            Complete project overview and performance report.
        </p>

    </div>


    <div class="table-actions">

        <a
            href="{{ route('admin.reports.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>


        <a
            href="{{ route(
                'admin.reports.print-project',
                $project
            ) }}"
            target="_blank"
            class="primary-btn"
        >
            🖨 Print Report
        </a>

    </div>

</div>



{{-- =====================================================
    PROJECT HEADER
====================================================== --}}

<div class="panel report-project-header">

    <div class="report-project-title">

        <div>

            <span class="report-label">
                PROJECT REPORT
            </span>

            <h2>
                {{ $project->project_name }}
            </h2>

        </div>


        @php

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


        <span class="status-badge {{ $statusClass }}">

            {{ ucfirst(
                str_replace(
                    '-',
                    ' ',
                    $project->status
                )
            ) }}

        </span>

    </div>

</div>



{{-- =====================================================
    PROJECT & CLIENT INFORMATION
====================================================== --}}

<div class="report-two-column">


    {{-- =================================================
        PROJECT INFORMATION
    ================================================== --}}

    <div class="panel report-section">

        <div class="report-section-header">

            <div>

                <h2>
                    Project Information
                </h2>

                <p>
                    Basic project details.
                </p>

            </div>

        </div>


        <div class="report-detail-list">


            <div class="report-detail-row">

                <span>
                    Project Name
                </span>

                <strong>
                    {{ $project->project_name }}
                </strong>

            </div>


            <div class="report-detail-row">

                <span>
                    Location
                </span>

                <strong>

                    {{ $project->location ?: '—' }}

                </strong>

            </div>


            <div class="report-detail-row">

                <span>
                    Start Date
                </span>

                <strong>

                    @if($project->start_date)

                        {{ \Carbon\Carbon::parse(
                            $project->start_date
                        )->format('d M Y') }}

                    @else

                        —

                    @endif

                </strong>

            </div>


            <div class="report-detail-row">

                <span>
                    End Date
                </span>

                <strong>

                    @if($project->end_date)

                        {{ \Carbon\Carbon::parse(
                            $project->end_date
                        )->format('d M Y') }}

                    @else

                        Ongoing

                    @endif

                </strong>

            </div>


            <div class="report-detail-row">

                <span>
                    Status
                </span>

                <strong>

                    {{ ucfirst(
                        str_replace(
                            '-',
                            ' ',
                            $project->status
                        )
                    ) }}

                </strong>

            </div>

        </div>

    </div>



    {{-- =================================================
        CLIENT INFORMATION
    ================================================== --}}

    <div class="panel report-section">

        <div class="report-section-header">

            <div>

                <h2>
                    Client Information
                </h2>

                <p>
                    Client details associated with this project.
                </p>

            </div>

        </div>


        <div class="report-detail-list">


            @if($project->client)


                <div class="report-detail-row">

                    <span>
                        Client Name
                    </span>

                    <strong>
                        {{ $project->client->name }}
                    </strong>

                </div>


                <div class="report-detail-row">

                    <span>
                        Phone
                    </span>

                    <strong>
                        {{ $project->client->phone ?: '—' }}
                    </strong>

                </div>


                <div class="report-detail-row">

                    <span>
                        Email
                    </span>

                    <strong>
                        {{ $project->client->email ?: '—' }}
                    </strong>

                </div>


                <div class="report-detail-row">

                    <span>
                        Address
                    </span>

                    <strong>
                        {{ $project->client->address ?: '—' }}
                    </strong>

                </div>


            @else

                <div class="report-empty-inline">

                    Client information not available.

                </div>

            @endif

        </div>

    </div>

</div>



{{-- =====================================================
    FINANCIAL SUMMARY
====================================================== --}}

<div class="panel report-section">

    <div class="report-section-header">

        <div>

            <h2>
                Financial Summary
            </h2>

            <p>
                Current project financial overview.
            </p>

        </div>

    </div>



    <div class="report-financial-grid">


        {{-- BUDGET --}}

        <div class="report-financial-card">

            <span>
                Estimated Budget
            </span>

            <strong>
                ৳{{ number_format(
                    $estimatedBudget,
                    2
                ) }}
            </strong>

        </div>



        {{-- MATERIAL --}}

        <div class="report-financial-card">

            <span>
                Material Cost
            </span>

            <strong>
                ৳{{ number_format(
                    $materialCost,
                    2
                ) }}
            </strong>

        </div>



        {{-- PAID --}}

        <div class="report-financial-card">

            <span>
                Total Paid
            </span>

            <strong>
                ৳{{ number_format(
                    $totalPaid,
                    2
                ) }}
            </strong>

        </div>



        {{-- REMAINING --}}

        <div class="report-financial-card">

            <span>
                Remaining Payment
            </span>

            <strong>
                ৳{{ number_format(
                    max(
                        $remainingPayment,
                        0
                    ),
                    2
                ) }}
            </strong>

        </div>

    </div>

</div>



{{-- =====================================================
    OVERALL PROGRESS
====================================================== --}}

<div class="panel report-section">

    <div class="report-section-header">

        <div>

            <h2>
                Overall Progress
            </h2>

            <p>
                Combined progress of all project work.
            </p>

        </div>


        <strong class="report-overall-progress-value">

            {{ $overallProgress }}%

        </strong>

    </div>



    <div class="report-overall-progress">

        <div class="report-overall-progress-bar">

            <div
                class="report-overall-progress-fill"
                style="
                    width: {{ min(
                        $overallProgress,
                        100
                    ) }}%;
                "
            ></div>

        </div>


        <div class="report-progress-status">

            @if($overallProgress >= 100)

                Project Work Completed

            @elseif($overallProgress > 0)

                Project Work In Progress

            @else

                No Progress Recorded

            @endif

        </div>

    </div>

</div>



{{-- =====================================================
    MATERIAL DETAILS
====================================================== --}}

<div class="panel report-section">

    <div class="report-section-header">

        <div>

            <h2>
                Material Details
            </h2>

            <p>
                Materials used for this project.
            </p>

        </div>


        <span class="table-count">

            {{ $project->projectMaterials->count() }}

            {{ $project->projectMaterials->count() === 1
                ? 'Item'
                : 'Items'
            }}

        </span>

    </div>



    <div class="table-wrapper">

        <table class="report-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        MATERIAL
                    </th>

                    <th>
                        QUANTITY
                    </th>

                    <th>
                        UNIT PRICE
                    </th>

                    <th>
                        TOTAL
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse(
                    $project->projectMaterials
                    as $material
                )

                    @php

                        $materialTotal =
                            isset($material->total_price)
                                ? (float)
                                    $material->total_price
                                : (
                                    (float)
                                    ($material->quantity ?? 0)
                                    *
                                    (float)
                                    ($material->unit_price ?? 0)
                                );

                    @endphp


                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <strong>

                                {{ $material->material_name
                                    ?? $material->name
                                    ?? '—'
                                }}

                            </strong>

                        </td>


                        <td>

                            {{ $material->quantity ?? '—' }}

                        </td>


                        <td>

                            ৳{{ number_format(
                                (float)
                                ($material->unit_price ?? 0),
                                2
                            ) }}

                        </td>


                        <td>

                            <strong>

                                ৳{{ number_format(
                                    $materialTotal,
                                    2
                                ) }}

                            </strong>

                        </td>

                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="5"
                            class="report-empty-state"
                        >

                            No material records found.

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
    PAYMENT HISTORY
====================================================== --}}

<div class="panel report-section">

    <div class="report-section-header">

        <div>

            <h2>
                Payment History
            </h2>

            <p>
                All payments recorded for this project.
            </p>

        </div>


        <span class="table-count">

            {{ $project->payments->count() }}

            {{ $project->payments->count() === 1
                ? 'Payment'
                : 'Payments'
            }}

        </span>

    </div>



    <div class="table-wrapper">

        <table class="report-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        DATE
                    </th>

                    <th>
                        AMOUNT
                    </th>

                    <th>
                        METHOD
                    </th>

                    <th>
                        NOTE
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse(
                    $project->payments->sortByDesc('payment_date')
                    as $payment
                )

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            {{ \Carbon\Carbon::parse(
                                $payment->payment_date
                            )->format('d M Y') }}

                        </td>


                        <td>

                            <strong>

                                ৳{{ number_format(
                                    $payment->amount,
                                    2
                                ) }}

                            </strong>

                        </td>


                        <td>

                            {{ $payment->payment_method }}

                        </td>


                        <td>

                            @if($payment->note)

                                {{ $payment->note }}

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="5"
                            class="report-empty-state"
                        >

                            No payment records found.

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
    WORK PROGRESS
====================================================== --}}

<div class="panel report-section">

    <div class="report-section-header">

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

        <table class="report-table">

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
                        UPDATED
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
                            min(
                                (int)
                                $progressReport->progress_percent,
                                100
                            );

                    @endphp


                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <strong>
                                {{ $progressReport->work_type }}
                            </strong>

                        </td>


                        <td>

                            <div class="report-progress">

                                <div
                                    class="report-progress-header"
                                >

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
                                            width:
                                            {{ $progress }}%"
                                        
                                    ></div>

                                </div>

                            </div>

                        </td>


                        <td>

                            @if(
                                $progressReport->description
                            )

                                {{ $progressReport->description }}

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        <td>

                            {{ $progressReport->updated_at
                                ? $progressReport
                                    ->updated_at
                                    ->format('d M Y')
                                : '—'
                            }}

                        </td>

                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="5"
                            class="report-empty-state"
                        >

                            No progress records found.

                        </td>

                    </tr>


                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- =====================================================
    REPORT FOOTER
====================================================== --}}

<div class="report-footer-actions">

    <a
        href="{{ route(
            'admin.reports.index'
        ) }}"
        class="secondary-btn"
    >
        ← Back to Reports
    </a>


    <a
        href="{{ route(
            'admin.reports.print-project',
            $project
        ) }}"
        target="_blank"
        class="primary-btn"
    >
        🖨 Print Report
    </a>

</div>


@endsection