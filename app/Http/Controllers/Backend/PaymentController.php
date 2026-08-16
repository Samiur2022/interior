<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Show payment summary project-wise.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | GET PROJECTS HAVING PAYMENTS
        |--------------------------------------------------------------------------
        */

        $projects = Project::with([
            'payments',
            'budget',
            'client',
        ])
        ->whereHas('payments')
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | CALCULATE PAYMENT SUMMARY
        |--------------------------------------------------------------------------
        */

        foreach ($projects as $project) {

            /*
            |--------------------------------------------------------------------------
            | TOTAL PAYMENT
            |--------------------------------------------------------------------------
            */

            $totalPaid = $project->payments->sum(
                'amount'
            );


            /*
            |--------------------------------------------------------------------------
            | BUDGET
            |--------------------------------------------------------------------------
            */

            $estimatedCost =
                $project->budget
                    ? (float) $project->budget->estimated_cost
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            $remaining =
                $estimatedCost - $totalPaid;


            /*
            |--------------------------------------------------------------------------
            | ATTACH CALCULATED VALUES
            |--------------------------------------------------------------------------
            */

            $project->total_paid =
                $totalPaid;

            $project->estimated_cost =
                $estimatedCost;

            $project->remaining_amount =
                $remaining;
        }


        return view(
            'backend.payments.index',
            compact('projects')
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     *
     * Show payment creation form.
     */
 public function create()
{
    $projects = Project::with([
        'payments',
        'budget',
    ])
    ->where('status', '!=', 'cancelled')
    ->orderBy('project_name')
    ->get();


    $projectData = [];

    foreach ($projects as $project) {

        $totalPaid = $project->payments->sum('amount');

        $budget = $project->budget
            ? (float) $project->budget->estimated_cost
            : null;

        $remaining = $budget !== null
            ? $budget - $totalPaid
            : null;


        $projectData[$project->id] = [

            'name' => $project->project_name,

            'status' => $project->status,

            'budget' => $budget,

            'total_paid' => (float) $totalPaid,

            'remaining' => $remaining,

        ];
    }


    return view(
        'backend.payments.create',
        compact(
            'projects',
            'projectData'
        )
    );
}


  /**
 * =========================================================
 * STORE
 * =========================================================
 *
 * Store a new payment.
 *
 * Rules:
 * - Cancelled project cannot receive payment.
 * - Project must have a budget.
 * - Full payment cannot be exceeded.
 * - Payment cannot be greater than remaining amount.
 */
public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'project_id' => [
            'required',
            'exists:projects,id',
        ],

        'amount' => [
            'required',
            'numeric',
            'min:0.01',
        ],

        'payment_date' => [
            'required',
            'date',
        ],

        'payment_method' => [
            'required',
            'string',
            'max:100',
        ],

        'note' => [
            'nullable',
            'string',
        ],

    ], [

        'project_id.required' =>
            'Please select a project.',

        'project_id.exists' =>
            'The selected project does not exist.',

        'amount.required' =>
            'Please enter the payment amount.',

        'amount.numeric' =>
            'Payment amount must be a valid number.',

        'amount.min' =>
            'Payment amount must be greater than zero.',

        'payment_date.required' =>
            'Please select the payment date.',

        'payment_date.date' =>
            'Please enter a valid payment date.',

        'payment_method.required' =>
            'Please select a payment method.',

        'payment_method.max' =>
            'Payment method cannot exceed 100 characters.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | GET PROJECT
    |--------------------------------------------------------------------------
    */

    $project = Project::with([
        'budget',
        'payments',
    ])->findOrFail(
        $validated['project_id']
    );


    /*
    |--------------------------------------------------------------------------
    | CANCELLED PROJECT CHECK
    |--------------------------------------------------------------------------
    */

    if ($project->status === 'cancelled') {

        return back()
            ->withErrors([
                'project_id' =>
                    'Payment cannot be added to a cancelled project.',
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT MUST HAVE A BUDGET
    |--------------------------------------------------------------------------
    */

    if (!$project->budget) {

        return back()
            ->withErrors([
                'project_id' =>
                    'This project does not have a budget yet. Please create a budget first.',
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT BUDGET
    |--------------------------------------------------------------------------
    */

    $budgetAmount =
        (float) $project->budget->estimated_cost;


    /*
    |--------------------------------------------------------------------------
    | TOTAL PAYMENT ALREADY RECEIVED
    |--------------------------------------------------------------------------
    */

    $totalPaid =
        (float) $project->payments->sum('amount');


    /*
    |--------------------------------------------------------------------------
    | REMAINING AMOUNT
    |--------------------------------------------------------------------------
    */

    $remainingAmount =
        $budgetAmount - $totalPaid;


    /*
    |--------------------------------------------------------------------------
    | FULL PAYMENT CHECK
    |--------------------------------------------------------------------------
    */

    if ($remainingAmount <= 0) {

        return back()
            ->withErrors([
                'amount' =>
                    'This project has already received the full budget amount. No further payment is allowed.',
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | NEW PAYMENT AMOUNT
    |--------------------------------------------------------------------------
    */

    $paymentAmount =
        (float) $validated['amount'];


    /*
    |--------------------------------------------------------------------------
    | PAYMENT CANNOT EXCEED REMAINING
    |--------------------------------------------------------------------------
    */

    if ($paymentAmount > $remainingAmount) {

        return back()
            ->withErrors([
                'amount' =>
                    'Payment amount cannot be greater than the remaining amount of ৳'
                    . number_format(
                        $remainingAmount,
                        2
                    )
                    . '.',
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE PAYMENT
    |--------------------------------------------------------------------------
    */

    Payment::create([

        'project_id' =>
            $project->id,

        'amount' =>
            $paymentAmount,

        'payment_date' =>
            $validated['payment_date'],

        'payment_method' =>
            $validated['payment_method'],

        'note' =>
            $validated['note'] ?? null,

    ]);


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.payments.index')
        ->with(
            'success',
            'Payment added successfully.'
        );
}


    /**
     * =========================================================
     * SHOW
     * =========================================================
     *
     * Show one payment with project payment summary.
     */
    public function show(Payment $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'project.client',
            'project.budget',
            'project.payments',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PROJECT
        |--------------------------------------------------------------------------
        */

        $project =
            $payment->project;


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAYMENT RECEIVED
        |--------------------------------------------------------------------------
        */

        $totalPaid =
            $project->payments->sum(
                'amount'
            );


        /*
        |--------------------------------------------------------------------------
        | ESTIMATED COST
        |--------------------------------------------------------------------------
        */

        $estimatedCost =
            $project->budget
                ? (float) $project->budget->estimated_cost
                : 0;


        /*
        |--------------------------------------------------------------------------
        | REMAINING AMOUNT
        |--------------------------------------------------------------------------
        */

        $remainingAmount =
            $estimatedCost - $totalPaid;


        /*
        |--------------------------------------------------------------------------
        | ATTACH CALCULATED VALUES
        |--------------------------------------------------------------------------
        */

        $payment->total_paid =
            $totalPaid;

        $payment->estimated_cost =
            $estimatedCost;

        $payment->remaining_amount =
            $remainingAmount;


        return view(
            'backend.payments.show',
            compact(
                'payment',
                'project',
                'totalPaid',
                'estimatedCost',
                'remainingAmount'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     *
     * Show payment edit form.
     */
    public function edit(Payment $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT
        |--------------------------------------------------------------------------
        */

        $payment->load('project');


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $payment->project &&
            $payment->project->status === 'cancelled'
        ) {

            return redirect()
                ->route(
                    'admin.payments.show',
                    $payment
                )
                ->with(
                    'error',
                    'Payment of a cancelled project cannot be edited.'
                );
        }


        return view(
            'backend.payments.edit',
            compact('payment')
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     *
     * Update existing payment.
     */
    public function update(
        Request $request,
        Payment $payment
    ) {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT
        |--------------------------------------------------------------------------
        */

        $payment->load('project');


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $payment->project &&
            $payment->project->status === 'cancelled'
        ) {

            return redirect()
                ->route(
                    'admin.payments.show',
                    $payment
                )
                ->with(
                    'error',
                    'Payment of a cancelled project cannot be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'note' => [
                'nullable',
                'string',
            ],

        ], [

            'amount.required' =>
                'Please enter the payment amount.',

            'amount.numeric' =>
                'Payment amount must be a valid number.',

            'amount.min' =>
                'Payment amount must be greater than zero.',

            'payment_date.required' =>
                'Please select the payment date.',

            'payment_date.date' =>
                'Please enter a valid payment date.',

            'payment_method.required' =>
                'Please select a payment method.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment->update([

            'amount' =>
                $validated['amount'],

            'payment_date' =>
                $validated['payment_date'],

            'payment_method' =>
                $validated['payment_method'],

            'note' =>
                $validated['note'] ?? null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.payments.show',
                $payment
            )
            ->with(
                'success',
                'Payment updated successfully.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * =========================================================
     *
     * Delete payment.
     *
     * POST only.
     */
    public function destroy(Payment $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | DELETE PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.payments.index')
            ->with(
                'success',
                'Payment deleted successfully.'
            );
    }
}