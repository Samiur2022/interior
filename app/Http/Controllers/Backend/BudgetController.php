<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    /**
     * =========================================================
     * Show all project budgets.
     * =========================================================
     *
     * 
     */
    public function index()
    {
        $budgets = Budget::with([
            'project',
        ])
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate variance in Controller
        |--------------------------------------------------------------------------
        */

        foreach ($budgets as $budget) {

            $estimatedCost =
                (float) $budget->estimated_cost;

            $actualCost =
                (float) ($budget->actual_cost ?? 0);

            $budget->variance =
                $estimatedCost - $actualCost;


            if ($budget->variance > 0) {

                $budget->variance_status =
                    'Under Budget';

            } elseif ($budget->variance < 0) {

                $budget->variance_status =
                    'Over Budget';

            } else {

                $budget->variance_status =
                    'On Budget';

            }
        }


        return view(
            'backend.budgets.index',
            compact('budgets')
        );
    }


    /**
     * =========================================================
     * Show create budget form.
     * =========================================================
     *
     * 
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Only projects without a budget
        |--------------------------------------------------------------------------
        |
        | One project = one budget.
        |
        */

        $projects = Project::whereDoesntHave('budget')
            ->orderBy('project_name')
            ->get();


        return view(
            'backend.budgets.create',
            compact('projects')
        );
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
     *
     * Create a budget for a project.
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
                Rule::unique('budgets', 'project_id'),
            ],

            'estimated_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'actual_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

        ], [

            'project_id.required' =>
                'Please select a project.',

            'project_id.exists' =>
                'The selected project does not exist.',

            'project_id.unique' =>
                'This project already has a budget.',

            'estimated_cost.required' =>
                'Please enter the estimated cost.',

            'estimated_cost.numeric' =>
                'Estimated cost must be a valid number.',

            'estimated_cost.min' =>
                'Estimated cost cannot be negative.',

            'actual_cost.numeric' =>
                'Actual cost must be a valid number.',

            'actual_cost.min' =>
                'Actual cost cannot be negative.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | GET PROJECT
        |--------------------------------------------------------------------------
        */

        $project = Project::findOrFail(
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
                        'A budget cannot be added to a cancelled project.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE BUDGET
        |--------------------------------------------------------------------------
        */

        Budget::create([

            'project_id' =>
                $project->id,

            'estimated_cost' =>
                $validated['estimated_cost'],

            'actual_cost' =>
                $validated['actual_cost'] ?? null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.budgets.index')
            ->with(
                'success',
                'Budget created successfully.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     *
     * Show one budget.
     */
    public function show(Budget $budget)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT
        |--------------------------------------------------------------------------
        */

        $budget->load([
            'project.client',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CALCULATE VARIANCE
        |--------------------------------------------------------------------------
        */

        $estimatedCost =
            (float) $budget->estimated_cost;

        $actualCost =
            (float) ($budget->actual_cost ?? 0);


        $variance =
            $estimatedCost - $actualCost;


        /*
        |--------------------------------------------------------------------------
        | VARIANCE STATUS
        |--------------------------------------------------------------------------
        */

        if ($variance > 0) {

            $varianceStatus =
                'Under Budget';

        } elseif ($variance < 0) {

            $varianceStatus =
                'Over Budget';

        } else {

            $varianceStatus =
                'On Budget';

        }


        return view(
            'backend.budgets.show',
            compact(
                'budget',
                'variance',
                'varianceStatus'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     *
     * Show edit budget form.
     */
    public function edit(Budget $budget)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT
        |--------------------------------------------------------------------------
        */

        $budget->load('project');


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $budget->project &&
            $budget->project->status === 'cancelled'
        ) {

            return redirect()
                ->route(
                    'admin.budgets.show',
                    $budget
                )
                ->with(
                    'error',
                    'Budget of a cancelled project cannot be edited.'
                );

        }


        return view(
            'backend.budgets.edit',
            compact('budget')
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     *
     * Update existing budget.
     */
    public function update(
        Request $request,
        Budget $budget
    ) {
        /*
        |--------------------------------------------------------------------------
        | LOAD PROJECT
        |--------------------------------------------------------------------------
        */

        $budget->load('project');


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $budget->project &&
            $budget->project->status === 'cancelled'
        ) {

            return redirect()
                ->route(
                    'admin.budgets.show',
                    $budget
                )
                ->with(
                    'error',
                    'Budget of a cancelled project cannot be edited.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'estimated_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'actual_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

        ], [

            'estimated_cost.required' =>
                'Please enter the estimated cost.',

            'estimated_cost.numeric' =>
                'Estimated cost must be a valid number.',

            'estimated_cost.min' =>
                'Estimated cost cannot be negative.',

            'actual_cost.numeric' =>
                'Actual cost must be a valid number.',

            'actual_cost.min' =>
                'Actual cost cannot be negative.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $budget->update([

            'estimated_cost' =>
                $validated['estimated_cost'],

            'actual_cost' =>
                $validated['actual_cost'] ?? null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.budgets.show',
                $budget
            )
            ->with(
                'success',
                'Budget updated successfully.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * ========================================================
     */
    public function destroy(Budget $budget)
    {
        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $budget->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.budgets.index')
            ->with(
                'success',
                'Budget deleted successfully.'
            );
    }
}