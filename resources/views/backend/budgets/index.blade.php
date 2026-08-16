@extends('backend.layouts.admin')

@section('title', 'Budgets')

@section('page_title', 'Budgets')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Budgets
        </h1>

        <p>
            Manage estimated and actual costs for each project.
        </p>

    </div>


    <a
        href="{{ route('admin.budgets.create') }}"
        class="primary-btn"
    >
        + Add Budget
    </a>

</div>



{{-- =====================================================
    BUDGET SUMMARY
====================================================== --}}

<div class="panel">


    {{-- =================================================
        PANEL HEADER
    ================================================== --}}

    <div class="panel-header">

        <div>

            <h2>
                Project Budget List
            </h2>

            <p>
                Compare estimated costs with actual project costs.
            </p>

        </div>


        <span class="table-count">

            {{ $budgets->count() }}

            {{ $budgets->count() === 1 ? 'Budget' : 'Budgets' }}

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


                    {{-- Estimated --}}

                    <th>
                        ESTIMATED COST
                    </th>


                    {{-- Actual --}}

                    <th>
                        ACTUAL COST
                    </th>


                    {{-- Variance --}}

                    <th>
                        VARIANCE
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


                @forelse($budgets as $budget)


                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | VARIANCE
                        |--------------------------------------------------------------------------
                        */

                        $variance =
                            (float) $budget->variance;


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        $varianceStatus =
                            $budget->variance_status;


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS CLASS
                        |--------------------------------------------------------------------------
                        */

                        $statusClass = match(
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


                        /*
                        |--------------------------------------------------------------------------
                        | VARIANCE CLASS
                        |--------------------------------------------------------------------------
                        */

                        $varianceClass =
                            $variance > 0
                                ? 'text-success'
                                : (
                                    $variance < 0
                                        ? 'text-danger'
                                        : 'text-muted'
                                );

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

                                {{ $budget->project->project_name
                                    ?? 'N/A'
                                }}

                            </strong>

                        </td>



                        {{-- =================================================
                            ESTIMATED COST
                        ================================================== --}}

                        <td>

                            <strong>

                                ৳{{ number_format(
                                    (float) $budget->estimated_cost,
                                    2
                                ) }}

                            </strong>

                        </td>



                        {{-- =================================================
                            ACTUAL COST
                        ================================================== --}}

                        <td>

                            @if($budget->actual_cost !== null)

                                ৳{{ number_format(
                                    (float) $budget->actual_cost,
                                    2
                                ) }}

                            @else

                                <span class="text-muted">
                                    Not Set
                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                            VARIANCE
                        ================================================== --}}

                        <td>

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

                        </td>



                        {{-- =================================================
                            VARIANCE STATUS
                        ================================================== --}}

                        <td>

                            <span
                                class="status-badge {{ $statusClass }}"
                            >

                                {{ $varianceStatus }}

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

                                <a
                                    href="{{ route(
                                        'admin.budgets.show',
                                        $budget
                                    ) }}"
                                    class="small-action view"
                                >
                                    View
                                </a>



                                {{-- =================================================
                                    EDIT
                                ================================================== --}}

                                @if(
                                    $budget->project &&
                                    $budget->project->status !== 'cancelled'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.budgets.edit',
                                            $budget
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
                                        'admin.budgets.destroy',
                                        $budget
                                    ) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="
                                        return confirm(
                                            'Are you sure you want to delete this budget?'
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
                                    No budgets found.
                                </strong>

                                <p>
                                    Create a budget for a project
                                    to see it here.
                                </p>


                                <a
                                    href="{{ route(
                                        'admin.budgets.create'
                                    ) }}"
                                    class="primary-btn"
                                >
                                    + Add Budget
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