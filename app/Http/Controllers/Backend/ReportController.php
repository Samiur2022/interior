<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Show project-wise summary reports.
    |
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'from_date' => [
                    'nullable',
                    'date',
                ],

                'to_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:from_date',
                ],
            ],
            [
                'from_date.date' =>
                    'Please enter a valid From Date.',

                'to_date.date' =>
                    'Please enter a valid To Date.',

                'to_date.after_or_equal' =>
                    'To Date must be the same as or later than From Date.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DATE VALUES
        |--------------------------------------------------------------------------
        */

        $fromDate =
            $validated['from_date'] ?? null;

        $toDate =
            $validated['to_date'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | PROJECT QUERY
        |--------------------------------------------------------------------------
        */

        $query = Project::with([
            'client',
            'budget',
            'payments',
            'progressReports',
            'projectMaterials',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        |
        | A project is included when its project period overlaps
        | the selected report period.
        |
        | Example:
        |
        | From: 16 Aug
        | To:   16 Aug
        |
        | A project active on 16 Aug will be included.
        |
        */

        if ($fromDate && $toDate) {

            $query->whereDate(
                'start_date',
                '<=',
                $toDate
            );

            $query->where(function ($q) use ($fromDate) {

                $q->whereDate(
                    'end_date',
                    '>=',
                    $fromDate
                )
                ->orWhereNull('end_date');

            });

        } elseif ($fromDate) {

            $query->whereDate(
                'start_date',
                '>=',
                $fromDate
            );

        } elseif ($toDate) {

            $query->whereDate(
                'start_date',
                '<=',
                $toDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $projects = $query
            ->orderBy(
                'start_date',
                'desc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | REPORT SUMMARY
        |--------------------------------------------------------------------------
        */

        $projects->each(function ($project) {

            /*
            |--------------------------------------------------------------------------
            | ESTIMATED BUDGET
            |--------------------------------------------------------------------------
            */

            $estimatedBudget =
                $project->budget
                    ? (float) $project->budget->estimated_cost
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | MATERIAL COST
            |--------------------------------------------------------------------------
            */

            $materialCost =
                $project->projectMaterials
                    ->sum(function ($material) {

                        /*
                        | If total_price exists,
                        | use it directly.
                        */

                        if (
                            isset(
                                $material->total_price
                            )
                        ) {

                            return (float)
                                $material->total_price;
                        }


                        /*
                        | Otherwise calculate:
                        |
                        | quantity × unit_price
                        */

                        return
                            (
                                (float)
                                ($material->quantity ?? 0)
                            )
                            *
                            (
                                (float)
                                ($material->unit_price ?? 0)
                            );

                    });


            /*
            |--------------------------------------------------------------------------
            | TOTAL PAID
            |--------------------------------------------------------------------------
            */

            $totalPaid =
                $project->payments
                    ->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | REMAINING PAYMENT
            |--------------------------------------------------------------------------
            */

            $remainingPayment =
                $estimatedBudget
                - $totalPaid;


            /*
            |--------------------------------------------------------------------------
            | OVERALL PROGRESS
            |--------------------------------------------------------------------------
            */

            $overallProgress =
                $project->progressReports
                    ->sum('progress_percent');


            /*
            |--------------------------------------------------------------------------
            | LIMIT PROGRESS TO 100
            |--------------------------------------------------------------------------
            */

            $overallProgress =
                min(
                    $overallProgress,
                    100
                );


            /*
            |--------------------------------------------------------------------------
            | ATTACH REPORT VALUES
            |--------------------------------------------------------------------------
            |
            | These are temporary attributes.
            | They are NOT stored in the database.
            |
            */

            $project->estimated_budget =
                $estimatedBudget;

            $project->material_cost =
                $materialCost;

            $project->total_paid =
                $totalPaid;

            $project->remaining_payment =
                $remainingPayment;

            $project->overall_progress =
                $overallProgress;

        });


        /*
        |--------------------------------------------------------------------------
        | TOTAL PROJECT COUNT
        |--------------------------------------------------------------------------
        */

        $projectCount =
            $projects->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL BUDGET
        |--------------------------------------------------------------------------
        */

        $totalBudget =
            $projects->sum(
                'estimated_budget'
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL MATERIAL COST
        |--------------------------------------------------------------------------
        */

        $totalMaterialCost =
            $projects->sum(
                'material_cost'
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAID
        |--------------------------------------------------------------------------
        */

        $totalPaid =
            $projects->sum(
                'total_paid'
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL REMAINING
        |--------------------------------------------------------------------------
        */

        $totalRemaining =
            $projects->sum(
                'remaining_payment'
            );


        /*
        |--------------------------------------------------------------------------
        | AVERAGE PROGRESS
        |--------------------------------------------------------------------------
        */

        $averageProgress =
            $projectCount > 0
                ? round(
                    $projects->avg(
                        'overall_progress'
                    )
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.index',
            compact(
                'projects',
                'projectCount',
                'totalBudget',
                'totalMaterialCost',
                'totalPaid',
                'totalRemaining',
                'averageProgress',
                'fromDate',
                'toDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE PROJECT REPORT
    |--------------------------------------------------------------------------
    |
    | Show complete report of one project.
    |
    */

    public function show(Project $project)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD ALL REQUIRED DATA
        |--------------------------------------------------------------------------
        */

        $project->load([
            'client',
            'budget',
            'payments',
            'progressReports',
            'projectMaterials',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUDGET
        |--------------------------------------------------------------------------
        */

        $estimatedBudget =
            $project->budget
                ? (float) $project->budget->estimated_cost
                : 0;


        /*
        |--------------------------------------------------------------------------
        | MATERIAL COST
        |--------------------------------------------------------------------------
        */

        $materialCost =
            $project->projectMaterials
                ->sum(function ($material) {

                    if (
                        isset(
                            $material->total_price
                        )
                    ) {

                        return (float)
                            $material->total_price;
                    }

                    return
                        (
                            (float)
                            ($material->quantity ?? 0)
                        )
                        *
                        (
                            (float)
                            ($material->unit_price ?? 0)
                        );

                });


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAID
        |--------------------------------------------------------------------------
        */

        $totalPaid =
            $project->payments
                ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | REMAINING PAYMENT
        |--------------------------------------------------------------------------
        */

        $remainingPayment =
            $estimatedBudget
            - $totalPaid;


        /*
        |--------------------------------------------------------------------------
        | OVERALL PROGRESS
        |--------------------------------------------------------------------------
        */

        $overallProgress =
            $project->progressReports
                ->sum('progress_percent');


        $overallProgress =
            min(
                $overallProgress,
                100
            );


        /*
        |--------------------------------------------------------------------------
        | RETURN SINGLE PROJECT REPORT
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.show',
            compact(
                'project',
                'estimatedBudget',
                'materialCost',
                'totalPaid',
                'remainingPayment',
                'overallProgress'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT INDEX REPORT
    |--------------------------------------------------------------------------
    |
    | Print the filtered project summary.
    |
    */

    public function print(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'from_date' => [
                    'nullable',
                    'date',
                ],

                'to_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:from_date',
                ],
            ],
            [
                'from_date.date' =>
                    'Please enter a valid From Date.',

                'to_date.date' =>
                    'Please enter a valid To Date.',

                'to_date.after_or_equal' =>
                    'To Date must be the same as or later than From Date.',
            ]
        );


        $fromDate =
            $validated['from_date'] ?? null;

        $toDate =
            $validated['to_date'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | PROJECT QUERY
        |--------------------------------------------------------------------------
        */

        $query = Project::with([
            'client',
            'budget',
            'payments',
            'progressReports',
            'projectMaterials',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($fromDate && $toDate) {

            $query->whereDate(
                'start_date',
                '<=',
                $toDate
            );

            $query->where(function ($q) use ($fromDate) {

                $q->whereDate(
                    'end_date',
                    '>=',
                    $fromDate
                )
                ->orWhereNull('end_date');

            });

        } elseif ($fromDate) {

            $query->whereDate(
                'start_date',
                '>=',
                $fromDate
            );

        } elseif ($toDate) {

            $query->whereDate(
                'start_date',
                '<=',
                $toDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET PROJECTS
        |--------------------------------------------------------------------------
        */

        $projects = $query
            ->orderBy(
                'start_date',
                'desc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CALCULATE REPORT DATA
        |--------------------------------------------------------------------------
        */

        $projects->each(function ($project) {

            $estimatedBudget =
                $project->budget
                    ? (float) $project->budget->estimated_cost
                    : 0;


            $materialCost =
                $project->projectMaterials
                    ->sum(function ($material) {

                        if (
                            isset(
                                $material->total_price
                            )
                        ) {

                            return (float)
                                $material->total_price;
                        }

                        return
                            (
                                (float)
                                ($material->quantity ?? 0)
                            )
                            *
                            (
                                (float)
                                ($material->unit_price ?? 0)
                            );

                    });


            $totalPaid =
                $project->payments
                    ->sum('amount');


            $remainingPayment =
                $estimatedBudget
                - $totalPaid;


            $overallProgress =
                $project->progressReports
                    ->sum('progress_percent');


            $project->estimated_budget =
                $estimatedBudget;

            $project->material_cost =
                $materialCost;

            $project->total_paid =
                $totalPaid;

            $project->remaining_payment =
                $remainingPayment;

            $project->overall_progress =
                min(
                    $overallProgress,
                    100
                );

        });


        /*
        |--------------------------------------------------------------------------
        | RETURN PRINT VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.print',
            compact(
                'projects',
                'fromDate',
                'toDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT SINGLE PROJECT REPORT
    |--------------------------------------------------------------------------
    */

    public function printProject(
        Project $project
    ) {
        /*
        |--------------------------------------------------------------------------
        | LOAD DATA
        |--------------------------------------------------------------------------
        */

        $project->load([
            'client',
            'budget',
            'payments',
            'progressReports',
            'projectMaterials',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CALCULATIONS
        |--------------------------------------------------------------------------
        */

        $estimatedBudget =
            $project->budget
                ? (float) $project->budget->estimated_cost
                : 0;


        $materialCost =
            $project->projectMaterials
                ->sum(function ($material) {

                    if (
                        isset(
                            $material->total_price
                        )
                    ) {

                        return (float)
                            $material->total_price;
                    }

                    return
                        (
                            (float)
                            ($material->quantity ?? 0)
                        )
                        *
                        (
                            (float)
                            ($material->unit_price ?? 0)
                        );

                });


        $totalPaid =
            $project->payments
                ->sum('amount');


        $remainingPayment =
            $estimatedBudget
            - $totalPaid;


        $overallProgress =
            $project->progressReports
                ->sum('progress_percent');


        $overallProgress =
            min(
                $overallProgress,
                100
            );


        /*
        |--------------------------------------------------------------------------
        | RETURN PRINT VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.print-project',
            compact(
                'project',
                'estimatedBudget',
                'materialCost',
                'totalPaid',
                'remainingPayment',
                'overallProgress'
            )
        );
    }
}