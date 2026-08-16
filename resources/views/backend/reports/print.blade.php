<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Project Reports
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
        }


        body {
            background: #ffffff;

            color: #111827;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 12px;

            line-height: 1.5;
        }


        .print-container {
            width: 100%;
            max-width: 1200px;

            margin: 0 auto;

            padding: 30px;
        }


        /* =================================================
           PRINT BUTTON
        ================================================== */

        .print-actions {
            margin-bottom: 25px;

            display: flex;

            justify-content: flex-end;

            gap: 10px;
        }


        .print-button {
            padding: 9px 16px;

            border: 0;

            border-radius: 6px;

            background: #2563eb;

            color: #ffffff;

            font-size: 13px;

            font-weight: 600;

            cursor: pointer;
        }


        .back-button {
            display: inline-flex;

            align-items: center;

            padding: 9px 16px;

            border: 1px solid #d1d5db;

            border-radius: 6px;

            background: #ffffff;

            color: #374151;

            font-size: 13px;

            text-decoration: none;
        }


        /* =================================================
           REPORT HEADER
        ================================================== */

        .report-header {
            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 30px;

            padding-bottom: 18px;

            margin-bottom: 20px;

            border-bottom: 2px solid #111827;
        }


        .report-title h1 {
            margin: 0 0 5px;

            font-size: 26px;

            font-weight: 700;

            color: #111827;
        }


        .report-title p {
            margin: 0;

            color: #6b7280;

            font-size: 12px;
        }


        .report-meta {
            text-align: right;

            color: #6b7280;

            font-size: 11px;
        }


        /* =================================================
           REPORT PERIOD
        ================================================== */

        .report-period {
            margin-bottom: 20px;

            padding: 12px 15px;

            border: 1px solid #d1d5db;

            background: #f8fafc;
        }


        .report-period-title {
            margin-bottom: 4px;

            color: #6b7280;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.05em;
        }


        .report-period-value {
            color: #111827;

            font-size: 14px;

            font-weight: 600;
        }


        /* =================================================
           SUMMARY
        ================================================== */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(6, 1fr);

            gap: 10px;

            margin-bottom: 25px;
        }


        .summary-card {
            padding: 12px;

            border: 1px solid #d1d5db;

            background: #ffffff;
        }


        .summary-label {
            display: block;

            margin-bottom: 5px;

            color: #6b7280;

            font-size: 10px;
        }


        .summary-value {
            display: block;

            color: #111827;

            font-size: 16px;

            font-weight: 700;
        }


        /* =================================================
           SECTION
        ================================================== */

        .section-title {
            margin: 0 0 12px;

            padding-bottom: 7px;

            border-bottom: 1px solid #d1d5db;

            font-size: 16px;

            font-weight: 700;
        }


        /* =================================================
           TABLE
        ================================================== */

        .report-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 25px;
        }


        .report-table th {
            padding: 9px 8px;

            border: 1px solid #d1d5db;

            background: #f3f4f6;

            color: #374151;

            font-size: 9px;

            font-weight: 700;

            text-align: left;

            text-transform: uppercase;

            white-space: nowrap;
        }


        .report-table td {
            padding: 9px 8px;

            border: 1px solid #d1d5db;

            color: #374151;

            font-size: 10px;

            vertical-align: middle;
        }


        .report-table td strong {
            color: #111827;
        }


        .text-center {
            text-align: center;
        }


        .text-right {
            text-align: right;
        }


        .muted {
            color: #9ca3af;
        }


        /* =================================================
           STATUS
        ================================================== */

        .status {
            display: inline-block;

            padding: 3px 7px;

            border-radius: 4px;

            background: #f3f4f6;

            color: #374151;

            font-size: 9px;

            font-weight: 700;

            text-transform: uppercase;

            white-space: nowrap;
        }


        /* =================================================
           PROGRESS
        ================================================== */

        .progress-wrapper {
            min-width: 90px;
        }


        .progress-value {
            margin-bottom: 4px;

            font-weight: 700;

            font-size: 10px;
        }


        .progress-track {
            width: 100%;

            height: 6px;

            overflow: hidden;

            border-radius: 20px;

            background: #e5e7eb;
        }


        .progress-fill {
            height: 100%;

            border-radius: 20px;

            background: #2563eb;
        }


        /* =================================================
           FOOTER
        ================================================== */

        .report-footer {
            display: flex;

            justify-content: space-between;

            gap: 20px;

            margin-top: 30px;

            padding-top: 12px;

            border-top: 1px solid #d1d5db;

            color: #6b7280;

            font-size: 9px;
        }


        /* =================================================
           EMPTY STATE
        ================================================== */

        .empty-state {
            padding: 35px !important;

            color: #9ca3af !important;

            text-align: center;
        }


        /* =================================================
           PRINT
        ================================================== */

        @media print {

            body {
                font-size: 10px;
            }


            .print-container {
                max-width: none;

                padding: 0;
            }


            .no-print {
                display: none !important;
            }


            .report-header {
                margin-bottom: 15px;
            }


            .summary-card {
                break-inside: avoid;
            }


            .report-table {
                page-break-inside: auto;
            }


            .report-table tr {
                page-break-inside: avoid;

                page-break-after: auto;
            }


            .report-table thead {
                display: table-header-group;
            }


            .section-title {
                break-after: avoid;
            }


            @page {
                size: A4 landscape;

                margin: 10mm;
            }

        }


        /* =================================================
           SCREEN RESPONSIVE
        ================================================== */

        @media screen and (max-width: 900px) {

            .print-container {
                padding: 20px;
            }


            .summary-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }


            .report-header {
                flex-direction: column;
            }


            .report-meta {
                text-align: left;
            }

        }


        @media screen and (max-width: 600px) {

            .summary-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }


            .print-actions {
                justify-content: flex-start;

                flex-wrap: wrap;
            }

        }

    </style>

</head>


<body>


<div class="print-container">


    {{-- =================================================
        SCREEN ACTIONS
    ================================================== --}}

    <div class="print-actions no-print">

        <a
            href="{{ route('admin.reports.index', [
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ]) }}"
            class="back-button"
        >
            ← Back
        </a>


        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            🖨 Print Report
        </button>

    </div>



    {{-- =================================================
        REPORT HEADER
    ================================================== --}}

    <div class="report-header">

        <div class="report-title">

            <h1>
                Project Report
            </h1>

            <p>
                Web-Based Interior Project Management System
            </p>

        </div>


        <div class="report-meta">

            <div>
                Generated:
                {{ now()->format('d M Y, h:i A') }}
            </div>


            <div>
                Total Projects:
                {{ $projectCount }}
            </div>

        </div>

    </div>



    {{-- =================================================
        REPORT PERIOD
    ================================================== --}}

    <div class="report-period">

        <div class="report-period-title">
            Report Period
        </div>


        <div class="report-period-value">

            @if($fromDate && $toDate)

                {{ \Carbon\Carbon::parse(
                    $fromDate
                )->format('d M Y') }}

                &nbsp; → &nbsp;

                {{ \Carbon\Carbon::parse(
                    $toDate
                )->format('d M Y') }}


            @elseif($fromDate)

                From
                {{ \Carbon\Carbon::parse(
                    $fromDate
                )->format('d M Y') }}


            @elseif($toDate)

                Up to
                {{ \Carbon\Carbon::parse(
                    $toDate
                )->format('d M Y') }}


            @else

                All Projects

            @endif

        </div>

    </div>



    {{-- =================================================
        SUMMARY
    ================================================== --}}

    <div class="summary-grid">


        {{-- PROJECTS --}}

        <div class="summary-card">

            <span class="summary-label">
                Total Projects
            </span>

            <span class="summary-value">
                {{ $projectCount }}
            </span>

        </div>



        {{-- BUDGET --}}

        <div class="summary-card">

            <span class="summary-label">
                Estimated Budget
            </span>

            <span class="summary-value">

                ৳{{ number_format(
                    $totalBudget,
                    2
                ) }}

            </span>

        </div>



        {{-- MATERIAL --}}

        <div class="summary-card">

            <span class="summary-label">
                Material Cost
            </span>

            <span class="summary-value">

                ৳{{ number_format(
                    $totalMaterialCost,
                    2
                ) }}

            </span>

        </div>



        {{-- PAID --}}

        <div class="summary-card">

            <span class="summary-label">
                Total Paid
            </span>

            <span class="summary-value">

                ৳{{ number_format(
                    $totalPaid,
                    2
                ) }}

            </span>

        </div>



        {{-- REMAINING --}}

        <div class="summary-card">

            <span class="summary-label">
                Remaining Payment
            </span>

            <span class="summary-value">

                ৳{{ number_format(
                    $totalRemaining,
                    2
                ) }}

            </span>

        </div>



        {{-- PROGRESS --}}

        <div class="summary-card">

            <span class="summary-label">
                Average Progress
            </span>

            <span class="summary-value">

                {{ $averageProgress }}%

            </span>

        </div>

    </div>



    {{-- =================================================
        PROJECT REPORT
    ================================================== --}}

    <h2 class="section-title">
        Project Summary
    </h2>


    <table class="report-table">

        <thead>

            <tr>

                <th>
                    #
                </th>

                <th>
                    Project
                </th>

                <th>
                    Client
                </th>

                <th>
                    Start Date
                </th>

                <th>
                    End Date
                </th>

                <th>
                    Budget
                </th>

                <th>
                    Material
                </th>

                <th>
                    Paid
                </th>

                <th>
                    Remaining
                </th>

                <th>
                    Progress
                </th>

                <th>
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($projects as $project)

                @php

                    $progress =
                        min(
                            (float)
                            ($project->overall_progress ?? 0),
                            100
                        );


                    $budget =
                        (float)
                        ($project->estimated_budget ?? 0);


                    $paid =
                        (float)
                        ($project->total_paid ?? 0);


                    $remaining =
                        max(
                            $budget - $paid,
                            0
                        );


                    $material =
                        (float)
                        ($project->material_cost ?? 0);

                @endphp


                <tr>


                    {{-- SERIAL --}}

                    <td class="text-center">
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

                        {{ $project->client
                            ? $project->client->name
                            : '—'
                        }}

                    </td>



                    {{-- START DATE --}}

                    <td>

                        {{ $project->start_date
                            ? \Carbon\Carbon::parse(
                                $project->start_date
                            )->format('d M Y')
                            : '—'
                        }}

                    </td>



                    {{-- END DATE --}}

                    <td>

                        {{ $project->end_date
                            ? \Carbon\Carbon::parse(
                                $project->end_date
                            )->format('d M Y')
                            : 'Ongoing'
                        }}

                    </td>



                    {{-- BUDGET --}}

                    <td class="text-right">

                        ৳{{ number_format(
                            $budget,
                            2
                        ) }}

                    </td>



                    {{-- MATERIAL --}}

                    <td class="text-right">

                        ৳{{ number_format(
                            $material,
                            2
                        ) }}

                    </td>



                    {{-- PAID --}}

                    <td class="text-right">

                        ৳{{ number_format(
                            $paid,
                            2
                        ) }}

                    </td>



                    {{-- REMAINING --}}

                    <td class="text-right">

                        ৳{{ number_format(
                            $remaining,
                            2
                        ) }}

                    </td>



                    {{-- PROGRESS --}}

                    <td>

                        <div class="progress-wrapper">

                            <div class="progress-value">
                                {{ $progress }}%
                            </div>


                            <div class="progress-track">

                                <div
                                    class="progress-fill"
                                    style="
                                        width:
                                        {{ $progress }}%;
                                    "
                                ></div>

                            </div>

                        </div>

                    </td>



                    {{-- STATUS --}}

                    <td>

                        <span class="status">

                            {{ ucfirst(
                                str_replace(
                                    '-',
                                    ' ',
                                    $project->status
                                )
                            ) }}

                        </span>

                    </td>


                </tr>


            @empty


                <tr>

                    <td
                        colspan="11"
                        class="empty-state"
                    >

                        No projects found for the selected
                        date range.

                    </td>

                </tr>


            @endforelse

        </tbody>

    </table>



    {{-- =================================================
        FOOTER
    ================================================== --}}

    <div class="report-footer">

        <span>
            Web-Based Interior Project Management System
        </span>


        <span>
            Project Report
        </span>

    </div>


</div>



</body>

</html>