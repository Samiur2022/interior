@extends('backend.layouts.admin')

@section('title', 'Payments')

@section('page_title', 'Payments')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Payments
        </h1>

        <p>
            View payment summaries for each project.
        </p>

    </div>


    <a
        href="{{ route('admin.payments.create') }}"
        class="primary-btn"
    >
        + Add Payment
    </a>

</div>



{{-- =====================================================
    PAYMENT SUMMARY
====================================================== --}}

<div class="panel">


    {{-- =================================================
        PANEL HEADER
    ================================================== --}}

    <div class="panel-header">

        <div>

            <h2>
                Project Payment List
            </h2>

            <p>
                Track total payments received and remaining amounts.
            </p>

        </div>


        <span class="table-count">

            {{ $projects->count() }}

            {{ $projects->count() === 1 ? 'Project' : 'Projects' }}

        </span>

    </div>



    {{-- =================================================
        TABLE
    ================================================== --}}

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    {{-- Serial --}}

                    <th>
                        #
                    </th>


                    {{-- Project --}}

                    <th>
                        PROJECT
                    </th>


                    {{-- Budget --}}

                    <th>
                        BUDGET
                    </th>


                    {{-- Total Paid --}}

                    <th>
                        TOTAL PAID
                    </th>


                    {{-- Remaining --}}

                    <th>
                        REMAINING
                    </th>


                    {{-- Status --}}

                    <th>
                        STATUS
                    </th>


                    {{-- Actions --}}

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
                        | VALUES FROM CONTROLLER
                        |--------------------------------------------------------------------------
                        */

                        $budget =
                            (float) $project->estimated_cost;


                        $totalPaid =
                            (float) $project->total_paid;


                        $remaining =
                            (float) $project->remaining_amount;


                        /*
                        |--------------------------------------------------------------------------
                        | PROJECT STATUS CLASS
                        |--------------------------------------------------------------------------
                        */

                        $statusClass = match(
                            $project->status
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


                        /*
                        |--------------------------------------------------------------------------
                        | REMAINING AMOUNT CLASS
                        |--------------------------------------------------------------------------
                        */

                        if ($remaining > 0) {

                            $remainingClass =
                                'text-danger';

                        } elseif ($remaining < 0) {

                            $remainingClass =
                                'text-success';

                        } else {

                            $remainingClass =
                                'text-muted';

                        }

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


                            @if($project->client)

                                <small
                                    style="
                                        display:block;
                                        margin-top:4px;
                                        color:#6b7280;
                                    "
                                >

                                    {{ $project->client->name }}

                                </small>

                            @endif

                        </td>



                        {{-- =================================================
                            BUDGET
                        ================================================== --}}

                        <td>

                            @if($project->budget)

                                <strong>

                                    ৳{{ number_format(
                                        $budget,
                                        2
                                    ) }}

                                </strong>

                            @else

                                <span class="text-muted">
                                    No Budget
                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                            TOTAL PAID
                        ================================================== --}}

                        <td>

                            <strong>

                                ৳{{ number_format(
                                    $totalPaid,
                                    2
                                ) }}

                            </strong>

                        </td>



                        {{-- =================================================
                            REMAINING
                        ================================================== --}}

                        <td>

                            @if($project->budget)

                                <strong
                                    class="{{ $remainingClass }}"
                                >

                                    @if($remaining > 0)

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

                            @else

                                <span class="text-muted">
                                    N/A
                                </span>

                            @endif

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


                                {{-- =================================================
                                    VIEW
                                ================================================== --}}

                                @php

                                    $firstPayment =
                                        $project->payments->first();

                                @endphp


                                @if($firstPayment)

                                    <a
                                        href="{{ route(
                                            'admin.payments.show',
                                            $firstPayment
                                        ) }}"
                                        class="small-action view"
                                    >
                                        View
                                    </a>

                                @endif



                                {{-- =================================================
                                    ADD PAYMENT
                                ================================================== --}}

                                @if(
                                    $project->status !== 'cancelled'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.payments.create'
                                        ) }}"
                                        class="small-action edit"
                                    >
                                        + Payment
                                    </a>

                                @else

                                    <span
                                        class="small-action disabled-action"
                                    >
                                        Cancelled
                                    </span>

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
                            colspan="7"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No payments found.
                                </strong>

                                <p>
                                    Add a payment to a project
                                    to see it here.
                                </p>


                                <a
                                    href="{{ route(
                                        'admin.payments.create'
                                    ) }}"
                                    class="primary-btn"
                                >
                                    + Add Payment
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