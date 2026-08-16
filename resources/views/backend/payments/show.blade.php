@extends('backend.layouts.admin')

@section('title', 'Payment Details')

@section('page_title', 'Payment Details')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Payment Details
        </h1>

        <p>
            Complete payment history for this project.
        </p>

    </div>


    <div class="table-actions">

        <a
            href="{{ route('admin.payments.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>


        @if(
            $project &&
            $project->status !== 'cancelled'
        )

            <a
                href="{{ route(
                    'admin.payments.edit',
                    $payment
                ) }}"
                class="primary-btn"
            >
                Edit Payment
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
                Basic information about this payment's project.
            </p>

        </div>


        {{-- =================================================
            PROJECT STATUS
        ================================================== --}}

        @php

            $projectStatus =
                $project->status ?? null;


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



    <div class="detail-grid">


        {{-- =================================================
            PROJECT
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Project
            </span>

            <strong class="detail-value">

                {{ $project->project_name ?? 'N/A' }}

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

                {{ $project->client->name ?? 'N/A' }}

            </strong>

        </div>



        {{-- =================================================
            CLIENT PHONE
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Client Phone
            </span>

            <strong class="detail-value">

                {{ $project->client->phone ?? 'N/A' }}

            </strong>

        </div>



        {{-- =================================================
            PAYMENT RECORD
        ================================================== --}}

        <div class="detail-item">

            <span class="detail-label">
                Selected Payment
            </span>

            <strong class="detail-value">

                #{{ $payment->id }}

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
                Current project payment position.
            </p>

        </div>

    </div>


    <div class="payment-detail-grid">


        {{-- =================================================
            PROJECT BUDGET
        ================================================== --}}

        <div class="payment-detail-card">

            <span>
                Project Budget
            </span>

            <strong>

                @if($estimatedCost > 0)

                    ৳{{ number_format(
                        (float) $estimatedCost,
                        2
                    ) }}

                @else

                    No Budget

                @endif

            </strong>

        </div>



        {{-- =================================================
            TOTAL PAID
        ================================================== --}}

        <div class="payment-detail-card">

            <span>
                Total Paid
            </span>

            <strong>

                ৳{{ number_format(
                    (float) $totalPaid,
                    2
                ) }}

            </strong>

        </div>



        {{-- =================================================
            REMAINING
        ================================================== --}}

        @php

            $remaining =
                (float) $remainingAmount;


            $remainingClass =
                $remaining > 0
                    ? 'text-danger'
                    : (
                        $remaining < 0
                            ? 'text-success'
                            : 'text-muted'
                    );

        @endphp


        <div class="payment-detail-card">

            <span>
                Remaining
            </span>

            <strong class="{{ $remainingClass }}">

                @if($estimatedCost <= 0)

                    N/A

                @elseif($remaining > 0)

                    ৳{{ number_format(
                        $remaining,
                        2
                    ) }}

                @elseif($remaining < 0)

                    -৳{{ number_format(
                        abs($remaining),
                        2
                    ) }}

                @else

                    ৳0.00

                @endif

            </strong>

        </div>



        {{-- =================================================
            CURRENT PAYMENT
        ================================================== --}}

        <div class="payment-detail-card">

            <span>
                Current Payment
            </span>

            <strong>

                ৳{{ number_format(
                    (float) $payment->amount,
                    2
                ) }}

            </strong>

        </div>

    </div>

</div>



{{-- =====================================================
    PAYMENT HISTORY
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Payment History
            </h2>

            <p>
                All payments received for this project.
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

        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        DATE
                    </th>

                    <th>
                        PAYMENT METHOD
                    </th>

                    <th>
                        AMOUNT
                    </th>

                    <th>
                        NOTE
                    </th>

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>



            <tbody>


                @forelse(
                    $project->payments->sortByDesc('payment_date')
                    as $historyPayment
                )


                    <tr>


                        {{-- =================================================
                            SERIAL
                        ================================================== --}}

                        <td>

                            {{ $loop->iteration }}

                        </td>



                        {{-- =================================================
                            DATE
                        ================================================== --}}

                        <td>

                            {{ \Carbon\Carbon::parse(
                                $historyPayment->payment_date
                            )->format('d M Y') }}

                        </td>



                        {{-- =================================================
                            PAYMENT METHOD
                        ================================================== --}}

                        <td>

                            <span class="payment-method-badge">

                                {{ $historyPayment->payment_method }}

                            </span>

                        </td>



                        {{-- =================================================
                            AMOUNT
                        ================================================== --}}

                        <td>

                            <strong>

                                ৳{{ number_format(
                                    (float) $historyPayment->amount,
                                    2
                                ) }}

                            </strong>

                        </td>



                        {{-- =================================================
                            NOTE
                        ================================================== --}}

                        <td>

                            @if($historyPayment->note)

                                <span class="payment-note">

                                    {{ $historyPayment->note }}

                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}

                        <td>

                            <div class="table-actions">


                                {{-- =================================================
                                    EDIT
                                ================================================== --}}

                                @if(
                                    $projectStatus !== 'cancelled'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.payments.edit',
                                            $historyPayment
                                        ) }}"
                                        class="small-action edit"
                                    >
                                        Edit
                                    </a>

                                @endif



                                {{-- =================================================
                                    DELETE
                                ================================================== --}}

                                <form
                                    action="{{ route(
                                        'admin.payments.destroy',
                                        $historyPayment
                                    ) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="
                                        return confirm(
                                            'Are you sure you want to delete this payment?'
                                        );
                                    "
                                >

                                    @csrf

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


                    <tr>

                        <td
                            colspan="6"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No payment history found.
                                </strong>

                                <p>
                                    No payment records are available
                                    for this project.
                                </p>

                            </div>

                        </td>

                    </tr>


                @endforelse


            </tbody>



            {{-- =================================================
                TOTAL
            ================================================== --}}

            @if($project->payments->count() > 0)

                <tfoot>

                    <tr>

                        <th
                            colspan="3"
                            style="text-align:right;"
                        >

                            TOTAL PAID

                        </th>


                        <th>

                            ৳{{ number_format(
                                (float) $totalPaid,
                                2
                            ) }}

                        </th>


                        <th colspan="2"></th>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>

</div>



{{-- =====================================================
    ADD PAYMENT
====================================================== --}}

@if(
    $projectStatus !== 'cancelled'
)

    <div class="form-actions">

        <a
            href="{{ route(
                'admin.payments.create'
            ) }}"
            class="primary-btn"
        >
            + Add New Payment
        </a>

    </div>

@endif


@endsection