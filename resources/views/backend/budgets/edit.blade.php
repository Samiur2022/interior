@extends('backend.layouts.admin')

@section('title', 'Edit Budget')

@section('page_title', 'Edit Budget')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Edit Budget
        </h1>

        <p>
            Update budget information for
            {{ $budget->project->project_name ?? 'this project' }}.
        </p>

    </div>


    <div class="table-actions">

        <a
            href="{{ route(
                'admin.budgets.show',
                $budget
            ) }}"
            class="secondary-btn"
        >
            ← Back
        </a>

    </div>

</div>



{{-- =====================================================
    MAIN PANEL
====================================================== --}}

<div class="panel">


    {{-- =================================================
        PANEL HEADER
    ================================================== --}}

    <div class="panel-header">

        <div>

            <h2>
                Budget Information
            </h2>

            <p>
                Update the estimated and actual project costs.
            </p>

        </div>


        {{-- =================================================
            PROJECT STATUS
        ================================================== --}}

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
        CANCELLED PROJECT
    ================================================== --}}

    @if($projectStatus === 'cancelled')

        <div class="alert alert-error">

            <span>
                This project is cancelled.
                Its budget cannot be edited.
            </span>

        </div>

    @endif



    {{-- =================================================
        FORM
    ================================================== --}}

    <div class="form-container">

        <form
            action="{{ route(
                'admin.budgets.update',
                $budget
            ) }}"
            method="POST"
            id="budgetForm"
        >

            @csrf



            {{-- =================================================
                PROJECT
            ================================================== --}}

            <div class="form-group">

                <label for="project_name">

                    Project

                </label>


                <input
                    type="text"
                    id="project_name"
                    value="{{ $budget->project->project_name ?? 'N/A' }}"
                    readonly
                >


                <small class="form-help">

                    Project cannot be changed from the budget edit page.

                </small>

            </div>



            {{-- =================================================
                COST GRID
            ================================================== --}}

            <div class="form-grid">


                {{-- =================================================
                    ESTIMATED COST
                ================================================== --}}

                <div class="form-group">

                    <label for="estimated_cost">

                        Estimated Cost

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="number"
                        name="estimated_cost"
                        id="estimated_cost"
                        value="{{ old(
                            'estimated_cost',
                            $budget->estimated_cost
                        ) }}"
                        min="0"
                        step="0.01"
                        placeholder="Enter estimated cost"
                        required
                        @disabled($projectStatus === 'cancelled')
                    >


                    @error('estimated_cost')

                        <small class="field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>



                {{-- =================================================
                    ACTUAL COST
                ================================================== --}}

                <div class="form-group">

                    <label for="actual_cost">

                        Actual Cost

                        <span class="optional">
                            Optional
                        </span>

                    </label>


                    <input
                        type="number"
                        name="actual_cost"
                        id="actual_cost"
                        value="{{ old(
                            'actual_cost',
                            $budget->actual_cost
                        ) }}"
                        min="0"
                        step="0.01"
                        placeholder="Enter actual cost"
                        @disabled($projectStatus === 'cancelled')
                    >


                    @error('actual_cost')

                        <small class="field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>



            {{-- =================================================
                BUDGET SUMMARY
            ================================================== --}}

            <div class="budget-preview">

                <div class="budget-preview-header">

                    <div>

                        <h3>
                            Budget Summary
                        </h3>

                        <p>
                            This summary updates automatically as you edit the costs.
                        </p>

                    </div>

                </div>



                <div class="budget-summary-grid">


                    {{-- =================================================
                        ESTIMATED
                    ================================================== --}}

                    <div class="budget-summary-card">

                        <span>
                            Estimated Cost
                        </span>

                        <strong id="previewEstimated">
                            ৳0.00
                        </strong>

                    </div>



                    {{-- =================================================
                        ACTUAL
                    ================================================== --}}

                    <div class="budget-summary-card">

                        <span>
                            Actual Cost
                        </span>

                        <strong id="previewActual">
                            ৳0.00
                        </strong>

                    </div>



                    {{-- =================================================
                        VARIANCE
                    ================================================== --}}

                    <div class="budget-summary-card">

                        <span>
                            Variance
                        </span>

                        <strong
                            id="previewVariance"
                            class="text-muted"
                        >
                            ৳0.00
                        </strong>

                    </div>



                    {{-- =================================================
                        STATUS
                    ================================================== --}}

                    <div class="budget-summary-card">

                        <span>
                            Budget Status
                        </span>

                        <strong>

                            <span
                                id="previewStatus"
                                class="status-badge status-info"
                            >
                                On Budget
                            </span>

                        </strong>

                    </div>

                </div>

            </div>




            {{-- =================================================
                FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route(
                        'admin.budgets.show',
                        $budget
                    ) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                @if($projectStatus !== 'cancelled')

                    <button
                        type="submit"
                        class="primary-btn"
                        id="updateBudgetButton"
                    >
                        Update Budget
                    </button>

                @endif

            </div>


        </form>

    </div>

</div>



{{-- =====================================================
    JAVASCRIPT
====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const estimatedInput =
        document.getElementById(
            'estimated_cost'
        );

    const actualInput =
        document.getElementById(
            'actual_cost'
        );

    const previewEstimated =
        document.getElementById(
            'previewEstimated'
        );

    const previewActual =
        document.getElementById(
            'previewActual'
        );

    const previewVariance =
        document.getElementById(
            'previewVariance'
        );

    const previewStatus =
        document.getElementById(
            'previewStatus'
        );

    const form =
        document.getElementById(
            'budgetForm'
        );



    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        return Number(value).toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | CALCULATE BUDGET
    |--------------------------------------------------------------------------
    */

    function calculateBudget() {


        const estimated =
            parseFloat(
                estimatedInput.value
            ) || 0;


        const actual =
            parseFloat(
                actualInput.value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | VARIANCE
        |--------------------------------------------------------------------------
        */

        const variance =
            estimated - actual;



        /*
        |--------------------------------------------------------------------------
        | ESTIMATED
        |--------------------------------------------------------------------------
        */

        previewEstimated.textContent =
            `৳${formatMoney(
                estimated
            )}`;



        /*
        |--------------------------------------------------------------------------
        | ACTUAL
        |--------------------------------------------------------------------------
        */

        if (
            actualInput.value === ''
            ||
            actualInput.value === null
        ) {

            previewActual.textContent =
                'Not Set';

        } else {

            previewActual.textContent =
                `৳${formatMoney(
                    actual
                )}`;

        }



        /*
        |--------------------------------------------------------------------------
        | RESET VARIANCE CLASSES
        |--------------------------------------------------------------------------
        */

        previewVariance.classList.remove(
            'text-success',
            'text-danger',
            'text-muted'
        );



        /*
        |--------------------------------------------------------------------------
        | VARIANCE
        |--------------------------------------------------------------------------
        */

        if (variance > 0) {

            previewVariance.textContent =
                `+৳${formatMoney(
                    Math.abs(variance)
                )}`;

            previewVariance.classList.add(
                'text-success'
            );

        }
        else if (variance < 0) {

            previewVariance.textContent =
                `-৳${formatMoney(
                    Math.abs(variance)
                )}`;

            previewVariance.classList.add(
                'text-danger'
            );

        }
        else {

            previewVariance.textContent =
                '৳0.00';

            previewVariance.classList.add(
                'text-muted'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | RESET STATUS CLASSES
        |--------------------------------------------------------------------------
        */

        previewStatus.classList.remove(
            'status-success',
            'status-danger',
            'status-info',
            'status-warning',
            'status-secondary'
        );



        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (variance > 0) {

            previewStatus.textContent =
                'Under Budget';

            previewStatus.classList.add(
                'status-success'
            );

        }
        else if (variance < 0) {

            previewStatus.textContent =
                'Over Budget';

            previewStatus.classList.add(
                'status-danger'
            );

        }
        else {

            previewStatus.textContent =
                'On Budget';

            previewStatus.classList.add(
                'status-info'
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | INPUT EVENTS
    |--------------------------------------------------------------------------
    */

    estimatedInput.addEventListener(
        'input',
        calculateBudget
    );


    actualInput.addEventListener(
        'input',
        calculateBudget
    );



    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT PROTECTION
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {


                const projectStatus =
                    @json($projectStatus);


                /*
                |--------------------------------------------------------------------------
                | CANCELLED PROJECT
                |--------------------------------------------------------------------------
                */

                if (
                    projectStatus ===
                    'cancelled'
                ) {

                    event.preventDefault();

                    alert(
                        'The budget of a cancelled project cannot be edited.'
                    );

                    return;

                }

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | INITIAL CALCULATION
    |--------------------------------------------------------------------------
    */

    calculateBudget();

});

</script>


@endsection