<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProgressReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressReportController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Show one row per project with overall progress.
     */
    public function index()
    {
        $projects = Project::with('progressReports')
            ->orderBy('project_name')
            ->get();

        return view(
            'backend.progress-reports.index',
            compact('projects')
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     *
     * Show form for adding/updating project work progress.
     */
    public function create()
    {
        $projects = Project::with('progressReports')
            ->orderBy('project_name')
            ->get();

        return view(
            'backend.progress-reports.create',
            compact('projects')
        );
    }


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

            'work_type' => [
                'required',
                'string',
                'max:255',
            ],

            'progress_percent' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ], [

            'project_id.required' =>
            'Please select a project.',

            'project_id.exists' =>
            'The selected project does not exist.',

            'work_type.required' =>
            'Please enter the work type.',

            'work_type.max' =>
            'Work type cannot exceed 255 characters.',

            'progress_percent.required' =>
            'Please enter the progress percentage.',

            'progress_percent.integer' =>
            'Progress percentage must be a whole number.',

            'progress_percent.min' =>
            'Progress percentage cannot be less than 0%.',

            'progress_percent.max' =>
            'Progress percentage cannot be greater than 100%.',

            'image.image' =>
            'The uploaded file must be a valid image.',

            'image.mimes' =>
            'Image must be JPG, JPEG, PNG or WEBP format.',

            'image.max' =>
            'Image size cannot exceed 2 MB.',

        ]);


        /*
    |--------------------------------------------------------------------------
    | FIND PROJECT
    |--------------------------------------------------------------------------
    */

        $project = Project::with('progressReports')
            ->findOrFail(
                $validated['project_id']
            );


        /*
    |--------------------------------------------------------------------------
    | PROJECT STATUS CHECK
    |--------------------------------------------------------------------------
    |
    | Progress can only be added to:
    | pending / ongoing
    |
    */

        if (in_array($project->status, [
            'on-hold',
            'completed',
            'cancelled',
        ])) {

            return back()
                ->withErrors([
                    'project_id' =>
                    'Progress cannot be added because this project is '
                        . str_replace(
                            '-',
                            ' ',
                            $project->status
                        )
                        . '.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | FIND EXISTING WORK
    |--------------------------------------------------------------------------
    |
    | Same project + same work type
    | means update existing record.
    |
    */

        $progressReport = ProgressReport::where(
            'project_id',
            $project->id
        )
            ->where(
                'work_type',
                $validated['work_type']
            )
            ->first();


        /*
    |--------------------------------------------------------------------------
    | CURRENT TOTAL PROGRESS
    |--------------------------------------------------------------------------
    */

        $currentTotal = $project
            ->progressReports
            ->sum('progress_percent');


        /*
    |--------------------------------------------------------------------------
    | IF WORK ALREADY EXISTS
    |--------------------------------------------------------------------------
    |
    | Remove the existing work's old progress first.
    |
    */

        if ($progressReport) {

            $currentTotal -=
                (int) $progressReport->progress_percent;
        }


        /*
    |--------------------------------------------------------------------------
    | NEW TOTAL PROGRESS
    |--------------------------------------------------------------------------
    */

        $newTotal =
            $currentTotal
            + (int) $validated['progress_percent'];


        /*
    |--------------------------------------------------------------------------
    | TOTAL CANNOT EXCEED 100%
    |--------------------------------------------------------------------------
    */

        if ($newTotal > 100) {

            $remainingAvailable =
                100 - $currentTotal;

            return back()
                ->withErrors([
                    'progress_percent' =>
                    'This progress would make the project total '
                        . $newTotal
                        . '%. Maximum available progress is '
                        . $remainingAvailable
                        . '%.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOAD
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('image')) {

            /*
        |--------------------------------------------------------------------------
        | DELETE OLD IMAGE
        |--------------------------------------------------------------------------
        */

            if (
                $progressReport &&
                $progressReport->image &&
                Storage::disk('public')
                ->exists($progressReport->image)
            ) {

                Storage::disk('public')
                    ->delete(
                        $progressReport->image
                    );
            }


            /*
        |--------------------------------------------------------------------------
        | STORE NEW IMAGE
        |--------------------------------------------------------------------------
        */

            $validated['image'] =
                $request->file('image')
                ->store(
                    'progress-reports',
                    'public'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | UPDATE EXISTING WORK
    |--------------------------------------------------------------------------
    */

        if ($progressReport) {

            $progressReport->update(
                $validated
            );

            return redirect()
                ->route(
                    'admin.progress-reports.index'
                )
                ->with(
                    'success',
                    'Progress updated successfully.'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | CREATE NEW WORK
    |--------------------------------------------------------------------------
    */

        ProgressReport::create(
            $validated
        );


        /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route(
                'admin.progress-reports.index'
            )
            ->with(
                'success',
                'Progress added successfully.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     *
     * Show all work progress of one project.
     */
    public function show(ProgressReport $progressReport)
    {
        $project = Project::with(
            'progressReports'
        )->findOrFail(
            $progressReport->project_id
        );




        /*
        |--------------------------------------------------------------------------
        | OVERALL PROGRESS
        |--------------------------------------------------------------------------
        */

        $overallProgress =
            $project->progressReports
            ->sum('progress_percent');


        return view(
            'backend.progress-reports.show',
            compact(
                'project',
                'overallProgress'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     *
     * Edit one specific work progress.
     */
    public function edit(
        ProgressReport $progressReport
    ) {

        $projects = Project::orderBy(
            'project_name'
        )->get();


        return view(
            'backend.progress-reports.edit',
            compact(
                'progressReport',
                'projects'
            )
        );
    }

    public function update(
        Request $request,
        ProgressReport $progressReport
    ) {

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

            'work_type' => [
                'required',
                'string',
                'max:255',
            ],

            'progress_percent' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ], [

            'project_id.required' =>
            'Please select a project.',

            'project_id.exists' =>
            'The selected project does not exist.',

            'work_type.required' =>
            'Please enter the work type.',

            'work_type.max' =>
            'Work type cannot exceed 255 characters.',

            'progress_percent.required' =>
            'Please enter the progress percentage.',

            'progress_percent.integer' =>
            'Progress percentage must be a whole number.',

            'progress_percent.min' =>
            'Progress percentage cannot be less than 0%.',

            'progress_percent.max' =>
            'Progress percentage cannot be greater than 100%.',

            'image.image' =>
            'The uploaded file must be a valid image.',

            'image.mimes' =>
            'Image must be JPG, JPEG, PNG or WEBP format.',

            'image.max' =>
            'Image size cannot exceed 2 MB.',

        ]);


        /*
    |--------------------------------------------------------------------------
    | FIND PROJECT
    |--------------------------------------------------------------------------
    */

        $project = Project::with('progressReports')
            ->findOrFail(
                $validated['project_id']
            );


        /*
    |--------------------------------------------------------------------------
    | PROJECT STATUS CHECK
    |--------------------------------------------------------------------------
    */

        if (in_array($project->status, [
            'on-hold',
            'completed',
            'cancelled',
        ])) {

            return back()
                ->withErrors([
                    'project_id' =>
                    'Progress cannot be updated because this project is '
                        . str_replace(
                            '-',
                            ' ',
                            $project->status
                        )
                        . '.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | DUPLICATE WORK TYPE CHECK
    |--------------------------------------------------------------------------
    |
    | If the user changes the work type,
    | make sure another record does not already
    | use the same project + work type.
    |
    */

        $duplicate = ProgressReport::where(
            'project_id',
            $project->id
        )
            ->where(
                'work_type',
                $validated['work_type']
            )
            ->where(
                'id',
                '!=',
                $progressReport->id
            )
            ->exists();


        if ($duplicate) {

            return back()
                ->withErrors([
                    'work_type' =>
                    'This work type already exists for this project.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | CURRENT TOTAL
    |--------------------------------------------------------------------------
    |
    | Remove the current record's old percentage
    | before calculating the new total.
    |
    */

        $currentTotal =
            $project->progressReports
            ->sum('progress_percent');


        $currentTotal -=
            (int) $progressReport->progress_percent;


        /*
    |--------------------------------------------------------------------------
    | NEW TOTAL
    |--------------------------------------------------------------------------
    */

        $newTotal =
            $currentTotal
            + (int) $validated['progress_percent'];


        /*
    |--------------------------------------------------------------------------
    | TOTAL CANNOT EXCEED 100%
    |--------------------------------------------------------------------------
    */

        if ($newTotal > 100) {

            $remainingAvailable =
                100 - $currentTotal;

            return back()
                ->withErrors([
                    'progress_percent' =>
                    'This update would make the project total '
                        . $newTotal
                        . '%. Maximum available progress is '
                        . $remainingAvailable
                        . '%.',
                ])
                ->withInput();
        }


        /*
    |--------------------------------------------------------------------------
    | IMAGE UPDATE
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('image')) {

            /*
        |--------------------------------------------------------------------------
        | DELETE OLD IMAGE
        |--------------------------------------------------------------------------
        */

            if (
                $progressReport->image &&
                Storage::disk('public')
                ->exists(
                    $progressReport->image
                )
            ) {

                Storage::disk('public')
                    ->delete(
                        $progressReport->image
                    );
            }


            /*
        |--------------------------------------------------------------------------
        | STORE NEW IMAGE
        |--------------------------------------------------------------------------
        */

            $validated['image'] =
                $request->file('image')
                ->store(
                    'progress-reports',
                    'public'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

        $progressReport->update(
            $validated
        );


        /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route(
                'admin.progress-reports.index'
            )
            ->with(
                'success',
                'Progress updated successfully.'
            );
    }

    /**
     * =========================================================
     * DESTROY
     * =========================================================
     *
     * Delete one work progress record.
     */
    public function destroy(
        ProgressReport $progressReport
    ) {

        /*
        |--------------------------------------------------------------------------
        | DELETE IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            $progressReport->image &&
            Storage::disk('public')
            ->exists(
                $progressReport->image
            )
        ) {

            Storage::disk('public')
                ->delete(
                    $progressReport->image
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE RECORD
        |--------------------------------------------------------------------------
        */

        $progressReport->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.progress-reports.index'
            )
            ->with(
                'success',
                'Progress deleted successfully.'
            );
    }
}
