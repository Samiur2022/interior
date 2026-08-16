@extends('backend.layouts.admin')

@section('title', 'Add Payment')

@section('page_title', 'Add Payment')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Payment
        </h1>

        <p>
            Record a payment received for a project.
        </p>

    </div>


    <a
        href="{{ route('admin.payments.index') }}"
        class="secondary-btn"
    >
        ← Back
    </a>

</div>



{{-- =====================================================
    MAIN PANEL
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Payment Information
            </h2>

            <p>
                Enter the payment details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.payments.store') }}"
            method="POST"
            id="paymentForm"
        >

            @csrf



            {{-- =================================================
                PROJECT
            ================================================== --}}

            <div class="form-group">

                <label for="project_id">

                    Project

                    <span class="required">
                        *
                    </span>

                </label>


                <select
                    name="project_id"
                    id="project_id"
                    required
                >

                    <option value="">
                        -- Select Project --
                    </option>


                    @foreach($projects as $project)

                        <option
                            value="{{ $project->id }}"
                            data-status="{{ $project->status }}"
                            {{ old('project_id') == $project->id
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $project->project_name }}

                            —
                            {{ ucfirst(
                                str_replace(
                                    '-',
                                    ' ',
                                    $project->status
                                )
                            ) }}

                        </option>

                    @endforeach

                </select>


                @error('project_id')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror


                <small
                    id="projectStatusMessage"
                    class="form-help"
                ></small>

            </div>



            {{-- =================================================
                PAYMENT SUMMARY
            ================================================== --}}

            <div
                class="payment-summary"
                id="paymentSummary"
            >

                <div class="payment-summary-header">

                    <div>

                        <h3>
                            Project Payment Summary
                        </h3>

                        <p>
                            Current financial position of the selected project.
                        </p>

                    </div>

                </div>


                <div class="payment-summary-grid">


                    {{-- =================================================
                        PROJECT BUDGET
                    ================================================== --}}

                    <div class="payment-summary-card">

                        <span>
                            Project Budget
                        </span>

                        <strong id="projectBudget">
                            Not Available
                        </strong>

                    </div>



                    {{-- =================================================
                        TOTAL PAID
                    ================================================== --}}

                    <div class="payment-summary-card">

                        <span>
                            Total Paid
                        </span>

                        <strong id="totalPaid">
                            ৳0.00
                        </strong>

                    </div>



                    {{-- =================================================
                        CURRENT REMAINING
                    ================================================== --}}

                    <div class="payment-summary-card">

                        <span>
                            Current Remaining
                        </span>

                        <strong
                            id="currentRemaining"
                            class="text-muted"
                        >
                            Not Available
                        </strong>

                    </div>



                    {{-- =================================================
                        AFTER THIS PAYMENT
                    ================================================== --}}

                    <div class="payment-summary-card">

                        <span>
                            Remaining After Payment
                        </span>

                        <strong
                            id="remainingAfterPayment"
                            class="text-muted"
                        >
                            Not Available
                        </strong>

                    </div>

                </div>

            </div>



            {{-- =================================================
                PAYMENT DETAILS
            ================================================== --}}

            <div class="form-grid">


                {{-- =================================================
                    AMOUNT
                ================================================== --}}

                <div class="form-group">

                    <label for="amount">

                        Payment Amount

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="number"
                        name="amount"
                        id="amount"
                        value="{{ old('amount') }}"
                        min="0.01"
                        step="0.01"
                        placeholder="Enter payment amount"
                        required
                    >


                    <small
                        id="amountMessage"
                        class="form-help"
                    ></small>


                    @error('amount')

                        <small class="field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>



                {{-- =================================================
                    PAYMENT DATE
                ================================================== --}}

                <div class="form-group">

                    <label for="payment_date">

                        Payment Date

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="date"
                        name="payment_date"
                        id="payment_date"
                        value="{{ old(
                            'payment_date',
                            now()->format('Y-m-d')
                        ) }}"
                        required
                    >


                    @error('payment_date')

                        <small class="field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>



            {{-- =================================================
                PAYMENT METHOD
            ================================================== --}}

            <div class="form-group">

                <label for="payment_method">

                    Payment Method

                    <span class="required">
                        *
                    </span>

                </label>


                <select
                    name="payment_method"
                    id="payment_method"
                    required
                >

                    <option value="">
                        -- Select Payment Method --
                    </option>

                    <option
                        value="Cash"
                        {{ old('payment_method') === 'Cash'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Cash
                    </option>

                    <option
                        value="Bank Transfer"
                        {{ old('payment_method') === 'Bank Transfer'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Bank Transfer
                    </option>

                    <option
                        value="Cheque"
                        {{ old('payment_method') === 'Cheque'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Cheque
                    </option>

                    <option
                        value="Mobile Banking"
                        {{ old('payment_method') === 'Mobile Banking'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Mobile Banking
                    </option>

                    <option
                        value="Other"
                        {{ old('payment_method') === 'Other'
                            ? 'selected'
                            : ''
                        }}
                    >
                        Other
                    </option>

                </select>


                @error('payment_method')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                NOTE
            ================================================== --}}

            <div class="form-group">

                <label for="note">

                    Note

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <textarea
                    name="note"
                    id="note"
                    rows="4"
                    placeholder="Enter any additional payment note..."
                >{{ old('note') }}</textarea>


                @error('note')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>





            {{-- =================================================
                FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.payments.index') }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                    id="savePaymentButton"
                >
                    Save Payment
                </button>

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
    | PROJECT DATA
    |--------------------------------------------------------------------------
    */

    const projectData = @json($projectData);



    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const projectSelect =
        document.getElementById('project_id');

    const amountInput =
        document.getElementById('amount');

    const projectBudget =
        document.getElementById('projectBudget');

    const totalPaid =
        document.getElementById('totalPaid');

    const currentRemaining =
        document.getElementById('currentRemaining');

    const remainingAfterPayment =
        document.getElementById('remainingAfterPayment');

    const projectStatusMessage =
        document.getElementById(
            'projectStatusMessage'
        );

    const amountMessage =
        document.getElementById(
            'amountMessage'
        );

    const saveButton =
        document.getElementById(
            'savePaymentButton'
        );

    const paymentForm =
        document.getElementById(
            'paymentForm'
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
    | RESET SUMMARY
    |--------------------------------------------------------------------------
    */

    function resetSummary() {

        projectBudget.textContent =
            'Not Available';

        totalPaid.textContent =
            '৳0.00';

        currentRemaining.textContent =
            'Not Available';

        remainingAfterPayment.textContent =
            'Not Available';


        currentRemaining.className =
            'text-muted';

        remainingAfterPayment.className =
            'text-muted';


        amountMessage.textContent =
            '';

        amountMessage.className =
            'form-help';


        amountInput.removeAttribute(
            'max'
        );

        amountInput.disabled =
            false;

        saveButton.disabled =
            false;

    }



    /*
    |--------------------------------------------------------------------------
    | SET AMOUNT ERROR
    |--------------------------------------------------------------------------
    */

    function showAmountError(message) {

        amountMessage.textContent =
            message;

        amountMessage.className =
            'field-error';

        saveButton.disabled =
            true;

    }



    /*
    |--------------------------------------------------------------------------
    | SET AMOUNT SUCCESS
    |--------------------------------------------------------------------------
    */

    function showAmountSuccess(message) {

        amountMessage.textContent =
            message;

        amountMessage.className =
            'form-help';

        saveButton.disabled =
            false;

    }



    /*
    |--------------------------------------------------------------------------
    | CALCULATE AFTER PAYMENT
    |--------------------------------------------------------------------------
    */

    function calculateAfterPayment() {


        const projectId =
            projectSelect.value;


        if (!projectId) {

            return;

        }


        const project =
            projectData[projectId];


        if (!project) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | NO BUDGET
        |--------------------------------------------------------------------------
        */

        if (project.budget === null) {

            remainingAfterPayment.textContent =
                'No Budget';

            remainingAfterPayment.className =
                'text-muted';

            showAmountError(
                'This project does not have a budget.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | REMAINING AMOUNT
        |--------------------------------------------------------------------------
        */

        const remaining =
            Number(project.remaining);


        /*
        |--------------------------------------------------------------------------
        | ALREADY FULLY PAID
        |--------------------------------------------------------------------------
        */

        if (remaining <= 0) {

            remainingAfterPayment.textContent =
                '৳0.00';

            remainingAfterPayment.className =
                'text-muted';

            amountInput.value =
                '';

            amountInput.disabled =
                true;

            amountInput.removeAttribute(
                'max'
            );

            showAmountError(
                'Payment Complete — no further payment is allowed.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ENABLE AMOUNT
        |--------------------------------------------------------------------------
        */

        amountInput.disabled =
            false;

        amountInput.max =
            remaining;


        /*
        |--------------------------------------------------------------------------
        | PAYMENT AMOUNT
        |--------------------------------------------------------------------------
        */

        const paymentAmount =
            parseFloat(
                amountInput.value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | NO AMOUNT YET
        |--------------------------------------------------------------------------
        */

        if (paymentAmount <= 0) {

            remainingAfterPayment.textContent =
                `৳${formatMoney(remaining)}`;

            remainingAfterPayment.className =
                'text-danger';

            amountMessage.textContent =
                `Maximum payment allowed: ৳${formatMoney(
                    remaining
                )}`;

            amountMessage.className =
                'form-help';

            saveButton.disabled =
                false;

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT EXCEEDS REMAINING
        |--------------------------------------------------------------------------
        */

        if (
            paymentAmount >
            remaining
        ) {

            const excess =
                paymentAmount - remaining;


            remainingAfterPayment.textContent =
                `-৳${formatMoney(
                    excess
                )}`;

            remainingAfterPayment.className =
                'text-danger';


            showAmountError(
                `Payment cannot exceed the remaining amount of ৳${formatMoney(
                    remaining
                )}.`
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | VALID PAYMENT
        |--------------------------------------------------------------------------
        */

        const afterPayment =
            remaining - paymentAmount;


        if (afterPayment > 0) {

            remainingAfterPayment.textContent =
                `৳${formatMoney(
                    afterPayment
                )}`;

            remainingAfterPayment.className =
                'text-danger';


            showAmountSuccess(
                `Maximum payment allowed: ৳${formatMoney(
                    remaining
                )}.`
            );

        }
        else {

            remainingAfterPayment.textContent =
                '৳0.00';

            remainingAfterPayment.className =
                'text-success';


            showAmountSuccess(
                'This payment will complete the project payment.'
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE PROJECT SUMMARY
    |--------------------------------------------------------------------------
    */

    function updatePaymentSummary() {


        const projectId =
            projectSelect.value;


        /*
        |--------------------------------------------------------------------------
        | NO PROJECT
        |--------------------------------------------------------------------------
        */

        if (!projectId) {

            resetSummary();

            projectStatusMessage.textContent =
                '';

            return;

        }


        const project =
            projectData[projectId];


        /*
        |--------------------------------------------------------------------------
        | INVALID PROJECT
        |--------------------------------------------------------------------------
        */

        if (!project) {

            resetSummary();

            showAmountError(
                'Invalid project selected.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | PROJECT STATUS
        |--------------------------------------------------------------------------
        */

        const formattedStatus =
            project.status
                .replace('-', ' ')
                .replace(/\b\w/g, function (letter) {
                    return letter.toUpperCase();
                });


        projectStatusMessage.textContent =
            `Project status: ${formattedStatus}`;

        projectStatusMessage.style.color =
            '#6b7280';



        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT
        |--------------------------------------------------------------------------
        */

        if (
            project.status ===
            'cancelled'
        ) {

            projectStatusMessage.textContent =
                'Payment cannot be added to a cancelled project.';

            projectStatusMessage.style.color =
                '#dc2626';

            amountInput.disabled =
                true;

            saveButton.disabled =
                true;

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | BUDGET
        |--------------------------------------------------------------------------
        */

        if (project.budget !== null) {

            projectBudget.textContent =
                `৳${formatMoney(
                    project.budget
                )}`;

        } else {

            projectBudget.textContent =
                'No Budget';

        }



        /*
        |--------------------------------------------------------------------------
        | TOTAL PAID
        |--------------------------------------------------------------------------
        */

        totalPaid.textContent =
            `৳${formatMoney(
                project.total_paid
            )}`;



        /*
        |--------------------------------------------------------------------------
        | CURRENT REMAINING
        |--------------------------------------------------------------------------
        */

        if (
            project.remaining !== null
        ) {

            if (
                project.remaining > 0
            ) {

                currentRemaining.textContent =
                    `৳${formatMoney(
                        project.remaining
                    )}`;

                currentRemaining.className =
                    'text-danger';

            }
            else if (
                project.remaining < 0
            ) {

                currentRemaining.textContent =
                    `-৳${formatMoney(
                        Math.abs(
                            project.remaining
                        )
                    )}`;

                currentRemaining.className =
                    'text-success';

            }
            else {

                currentRemaining.textContent =
                    '৳0.00';

                currentRemaining.className =
                    'text-muted';

            }

        } else {

            currentRemaining.textContent =
                'No Budget';

            currentRemaining.className =
                'text-muted';

        }



        /*
        |--------------------------------------------------------------------------
        | CALCULATE PAYMENT
        |--------------------------------------------------------------------------
        */

        calculateAfterPayment();

    }



    /*
    |--------------------------------------------------------------------------
    | PROJECT CHANGE
    |--------------------------------------------------------------------------
    */

    projectSelect.addEventListener(
        'change',
        function () {

            amountInput.value =
                '';

            updatePaymentSummary();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | PAYMENT AMOUNT CHANGE
    |--------------------------------------------------------------------------
    */

    amountInput.addEventListener(
        'input',
        function () {

            calculateAfterPayment();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT PROTECTION
    |--------------------------------------------------------------------------
    */

    paymentForm.addEventListener(
        'submit',
        function (event) {


            const projectId =
                projectSelect.value;


            /*
            |--------------------------------------------------------------------------
            | PROJECT REQUIRED
            |--------------------------------------------------------------------------
            */

            if (!projectId) {

                event.preventDefault();

                alert(
                    'Please select a project.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PROJECT DATA
            |--------------------------------------------------------------------------
            */

            const project =
                projectData[projectId];


            if (!project) {

                event.preventDefault();

                alert(
                    'Invalid project selected.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | CANCELLED PROJECT
            |--------------------------------------------------------------------------
            */

            if (
                project.status ===
                'cancelled'
            ) {

                event.preventDefault();

                alert(
                    'Payment cannot be added to a cancelled project.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | NO BUDGET
            |--------------------------------------------------------------------------
            */

            if (
                project.budget === null
            ) {

                event.preventDefault();

                alert(
                    'This project does not have a budget yet.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            const remaining =
                Number(
                    project.remaining
                );


            /*
            |--------------------------------------------------------------------------
            | FULLY PAID
            |--------------------------------------------------------------------------
            */

            if (
                remaining <= 0
            ) {

                event.preventDefault();

                alert(
                    'This project has already received the full budget amount.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT AMOUNT
            |--------------------------------------------------------------------------
            */

            const paymentAmount =
                parseFloat(
                    amountInput.value
                ) || 0;


            /*
            |--------------------------------------------------------------------------
            | INVALID AMOUNT
            |--------------------------------------------------------------------------
            */

            if (
                paymentAmount <= 0
            ) {

                event.preventDefault();

                alert(
                    'Please enter a valid payment amount.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | EXCEEDS REMAINING
            |--------------------------------------------------------------------------
            */

            if (
                paymentAmount >
                remaining
            ) {

                event.preventDefault();

                alert(
                    `Payment cannot exceed the remaining amount of ৳${formatMoney(
                        remaining
                    )}.`
                );

                return;

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    updatePaymentSummary();

});

</script>


@endsection