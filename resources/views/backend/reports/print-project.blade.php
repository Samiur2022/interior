<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $project->project_name }} - Project Report
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            padding: 30px;

            background: #ffffff;

            color: #111827;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;
            line-height: 1.5;
        }


        .print-container {
            width: 100%;
            max-width: 1100px;

            margin: 0 auto;
        }


        /* =================================================
           HEADER
        ================================================= */

        .report-header {
            display: flex;

            justify-content: space-between;
            align-items: flex-start;

            padding-bottom: 20px;

            border-bottom: 2px solid #111827;

            margin-bottom: 25px;
        }


        .report-title h1 {
            margin: 0 0 5px;

            font-size: 26px;
            font-weight: 700;
        }


        .report-title p {
            margin: 0;

            color: #6b7280;

            font-size: 12px;
        }


        .report-meta {
            text-align: right;

            color: #6b7280;

            font-size: 12px;
        }


        /* =================================================
           PROJECT TITLE
        ================================================= */

        .project-heading {
            margin-bottom: 20px;

            padding: 15px 18px;

            border: 1px solid #d1d5db;

            background: #f8fafc;
        }


        .project-heading h2 {
            margin: 0 0 5px;

            font-size: 20px;
        }


        .project-status {
            display: inline-block;

            padding: 4px 10px;

            border-radius: 4px;

            background: #e5e7eb;

            font-size: 11px;
            font-weight: 600;

            text-transform: uppercase;
        }


        /* =================================================
           SECTION
        ================================================= */

        .section {
            margin-bottom: 22px;
        }


        .section-title {
            margin: 0 0 12px;

            padding-bottom: 7px;

            border-bottom: 1px solid #d1d5db;

            font-size: 16px;
            font-weight: 700;
        }


        /* =================================================
           TWO COLUMN
        ================================================= */

        .two-column {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 20px;

            margin-bottom: 22px;
        }


        .info-box {
            border: 1px solid #d1d5db;
        }


        .info-box-title {
            padding: 10px 14px;

            background: #f3f4f6;

            border-bottom: 1px solid #d1d5db;

            font-weight: 700;
        }


        .info-row {
            display: flex;

            justify-content: space-between;

            gap: 20px;

            padding: 9px 14px;

            border-bottom: 1px solid #e5e7eb;
        }


        .info-row:last-child {
            border-bottom: 0;
        }


        .info-label {
            color: #6b7280;
        }


        .info-value {
            font-weight: 600;

            text-align: right;
        }


        /* =================================================
           FINANCIAL SUMMARY
        ================================================= */

        .financial-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 12px;
        }


        .financial-box {
            padding: 14px;

            border: 1px solid #d1d5db;
        }


        .financial-label {
            display: block;

            margin-bottom: 5px;

            color: #6b7280;

            font-size: 11px;
        }


        .financial-value {
            font-size: 17px;
            font-weight: 700;
        }


        /* =================================================
           PROGRESS
        ================================================= */

        .overall-progress {
            margin-bottom: 15px;
        }


        .progress-header {
            display: flex;

            justify-content: space-between;

            margin-bottom: 7px;

            font-weight: 700;
        }


        .progress-track {
            width: 100%;
            height: 10px;

            overflow: hidden;

            background: #e5e7eb;

            border-radius: 20px;
        }


        .progress-fill {
            height: 100%;

            background: #2563eb;

            border-radius: 20px;
        }


        /* =================================================
           TABLE
        ================================================= */

        table {
            width: 100%;

            border-collapse: collapse;
        }


        th {
            padding: 9px 10px;

            background: #f3f4f6;

            border: 1px solid #d1d5db;

            color: #374151;

            font-size: 10px;
            font-weight: 700;

            text-align: left;

            text-transform: uppercase;
        }


        td {
            padding: 9px 10px;

            border: 1px solid #d1d5db;

            vertical-align: top;

            font-size: 11px;
        }


        .text-right {
            text-align: right;
        }


        .text-center {
            text-align: center;
        }


        .muted {
            color: #9ca3af;
        }


        /* =================================================
           FOOTER
        ================================================= */

        .report-footer {
            margin-top: 30px;

            padding-top: 12px;

            border-top: 1px solid #d1d5db;

            color: #6b7280;

            font-size: 10px;

            display: flex;

            justify-content: space-between;
        }


        /* =================================================
           PRINT
        ================================================= */

        @media print {

            body {
                padding: 0;

                font-size: 11px;
            }


            .print-container {
                max-width: none;
            }


            .no-print {
                display: none !important;
            }


            .section {
                break-inside: avoid;
            }


            .info-box,
            .financial-box {
                break-inside: avoid;
            }


            table {
                page-break-inside: auto;
            }


            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }


            thead {
                display: table-header-group;
            }


            @page {
                size: A4;
                margin: 12mm;
            }

        }


        /* =================================================
           SCREEN BUTTON
        ================================================= */

        .print-button {
            margin-bottom: 20px;

            padding: 9px 16px;

            border: 0;

            border-radius: 6px;

            background: #2563eb;

            color: #ffffff;

            cursor: pointer;

            font-size: 13px;
            font-weight: 600;
        }

    </style>

</head>


<body>


<div class="print-container">


    {{-- =================================================
        PRINT BUTTON
    ================================================= --}}

    <div class="no-print">

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            🖨 Print Report
        </button>

    </div>



    {{-- =================================================
        HEADER
    ================================================= --}}

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

            Generated:
            {{ now()->format('d M Y, h:i A') }}

        </div>

    </div>



    {{-- =================================================
        PROJECT HEADING
    ================================================= --}}

    <div class="project-heading">

        <h2>
            {{ $project->project_name }}
        </h2>


        <span class="project-status">

            {{ ucfirst(
                str_replace(
                    '-',
                    ' ',
                    $project->status
                )
            ) }}

        </span>

    </div>



    {{-- =================================================
        PROJECT + CLIENT
    ================================================= --}}

    <div class="two-column">


        {{-- PROJECT INFORMATION --}}

        <div class="info-box">

            <div class="info-box-title">
                Project Information
            </div>


            <div class="info-row">

                <span class="info-label">
                    Project Name
                </span>

                <span class="info-value">
                    {{ $project->project_name }}
                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Location
                </span>

                <span class="info-value">
                    {{ $project->location ?: '—' }}
                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Start Date
                </span>

                <span class="info-value">

                    {{ $project->start_date
                        ? \Carbon\Carbon::parse(
                            $project->start_date
                        )->format('d M Y')
                        : '—'
                    }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    End Date
                </span>

                <span class="info-value">

                    {{ $project->end_date
                        ? \Carbon\Carbon::parse(
                            $project->end_date
                        )->format('d M Y')
                        : 'Ongoing'
                    }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Status
                </span>

                <span class="info-value">

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



        {{-- CLIENT INFORMATION --}}

        <div class="info-box">

            <div class="info-box-title">
                Client Information
            </div>


            @if($project->client)

                <div class="info-row">

                    <span class="info-label">
                        Name
                    </span>

                    <span class="info-value">
                        {{ $project->client->name }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="info-label">
                        Phone
                    </span>

                    <span class="info-value">
                        {{ $project->client->phone ?: '—' }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="info-label">
                        Email
                    </span>

                    <span class="info-value">
                        {{ $project->client->email ?: '—' }}
                    </span>

                </div>


                <div class="info-row">

                    <span class="info-label">
                        Address
                    </span>

                    <span class="info-value">
                        {{ $project->client->address ?: '—' }}
                    </span>

                </div>

            @else

                <div class="info-row">

                    <span class="muted">
                        Client information unavailable.
                    </span>

                </div>

            @endif

        </div>

    </div>



    {{-- =================================================
        FINANCIAL SUMMARY
    ================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Financial Summary
        </h2>


        <div class="financial-grid">


            <div class="financial-box">

                <span class="financial-label">
                    Estimated Budget
                </span>

                <strong class="financial-value">

                    ৳{{ number_format(
                        $estimatedBudget,
                        2
                    ) }}

                </strong>

            </div>


            <div class="financial-box">

                <span class="financial-label">
                    Material Cost
                </span>

                <strong class="financial-value">

                    ৳{{ number_format(
                        $materialCost,
                        2
                    ) }}

                </strong>

            </div>


            <div class="financial-box">

                <span class="financial-label">
                    Total Paid
                </span>

                <strong class="financial-value">

                    ৳{{ number_format(
                        $totalPaid,
                        2
                    ) }}

                </strong>

            </div>


            <div class="financial-box">

                <span class="financial-label">
                    Remaining Payment
                </span>

                <strong class="financial-value">

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



    {{-- =================================================
        OVERALL PROGRESS
    ================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Overall Progress
        </h2>


        <div class="overall-progress">

            <div class="progress-header">

                <span>
                    Project Completion
                </span>

                <span>
                    {{ $overallProgress }}%
                </span>

            </div>


            <div class="progress-track">

                <div
                    class="progress-fill"
                    style="
                        width:
                        {{ min(
                            $overallProgress,
                            100
                        ) }}%;
                    "
                ></div>

            </div>

        </div>

    </div>



    {{-- =================================================
        MATERIAL DETAILS
    ================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Material Details
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Material
                    </th>

                    <th>
                        Quantity
                    </th>

                    <th>
                        Unit Price
                    </th>

                    <th>
                        Total
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
                            isset(
                                $material->total_price
                            )
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

                            {{
                                $material->material_name
                                ?? $material->name
                                ?? '—'
                            }}

                        </td>

                        <td>
                            {{ $material->quantity ?? '—' }}
                        </td>

                        <td class="text-right">

                            ৳{{ number_format(
                                (float)
                                ($material->unit_price ?? 0),
                                2
                            ) }}

                        </td>

                        <td class="text-right">

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
                            class="text-center muted"
                        >
                            No material records found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- =================================================
        PAYMENT HISTORY
    ================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Payment History
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Date
                    </th>

                    <th>
                        Amount
                    </th>

                    <th>
                        Method
                    </th>

                    <th>
                        Note
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse(
                    $project->payments
                        ->sortByDesc('payment_date')
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

                        <td class="text-right">

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

                            {{ $payment->note ?: '—' }}

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center muted"
                        >
                            No payment records found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- =================================================
        WORK PROGRESS
    ================================================== --}}

    <div class="section">

        <h2 class="section-title">
            Work Progress
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Work Type
                    </th>

                    <th>
                        Progress
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Updated
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
                                $progressReport
                                    ->progress_percent,
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

                            {{ $progress }}%

                        </td>

                        <td>

                            {{ $progressReport->description
                                ?: '—'
                            }}

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
                            class="text-center muted"
                        >
                            No progress records found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- =================================================
        FOOTER
    ================================================== --}}

    <div class="report-footer">

        <span>
            Web-Based Interior Project Management System
        </span>


        <span>
            {{ $project->project_name }}
        </span>

    </div>


</div>



</body>

</html>