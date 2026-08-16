<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\ProjectMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectMaterialController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Show one row per project that has materials.
     */
    public function index()
    {
        $projects = Project::with([
            'client',
            'projectMaterials',
        ])
        ->whereHas('projectMaterials')
        ->latest()
        ->get();

        return view(
            'backend.project-materials.index',
            compact('projects')
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     *
     * Show form for adding multiple materials to a project.
     */
    public function create()
    {
        $projects = Project::orderBy('project_name')
            ->get();

        $materials = Material::orderBy('material_name')
            ->get();

        $suppliers = Supplier::orderBy('supplier_name')
            ->get();

        return view(
            'backend.project-materials.create',
            compact(
                'projects',
                'materials',
                'suppliers'
            )
        );
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
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

            'materials' => [
                'required',
                'array',
                'min:1',
            ],

            'materials.*.material_id' => [
                'required',
                'exists:materials,id',
            ],

            'materials.*.supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'materials.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

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
        |
        */

        if ($project->status === 'cancelled') {

            return back()
                ->withErrors([
                    'project_id' =>
                        'Materials cannot be added to a cancelled project.'
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $project
        ) {

            foreach (
                $validated['materials']
                as $materialData
            ) {

                /*
                |--------------------------------------------------------------------------
                | GET MATERIAL FROM DATABASE
                |--------------------------------------------------------------------------
                |
                */

                $material = Material::findOrFail(
                    $materialData['material_id']
                );


                /*
                |--------------------------------------------------------------------------
                | UNIT PRICE
                |--------------------------------------------------------------------------
                */

                $unitPrice =
                    (float) $material->unit_price;


                /*
                |--------------------------------------------------------------------------
                | QUANTITY
                |--------------------------------------------------------------------------
                */

                $quantity =
                    (float) $materialData['quantity'];


                /*
                |--------------------------------------------------------------------------
                | TOTAL PRICE
                |--------------------------------------------------------------------------
                */

                $totalPrice =
                    $quantity * $unitPrice;


                /*
                |--------------------------------------------------------------------------
                | CREATE PROJECT MATERIAL
                |--------------------------------------------------------------------------
                */

                ProjectMaterial::create([

                    'project_id' =>
                        $project->id,

                    'material_id' =>
                        $material->id,

                    'supplier_id' =>
                        $materialData['supplier_id'],

                    'quantity' =>
                        $quantity,

                    'unit_price' =>
                        $unitPrice,

                    'total_price' =>
                        $totalPrice,

                    'status' =>
                        'active',

                ]);

            }

        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.project-materials.index')
            ->with(
                'success',
                'Project materials added successfully.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     *
     * Show complete project material invoice.
     */
    public function show(
        ProjectMaterial $projectMaterial
    ) {

        /*
        |--------------------------------------------------------------------------
        | GET PROJECT
        |--------------------------------------------------------------------------
        */

        $project = Project::with([
            'client',
            'projectMaterials.material',
            'projectMaterials.supplier',
        ])
        ->findOrFail(
            $projectMaterial->project_id
        );


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $project->projectMaterials
                ->sum('total_price');


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.project-materials.show',
            compact(
                'project',
                'grandTotal'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     *
     * Show all materials of the selected project
     * inside one edit form.
     */
    public function edit(
        ProjectMaterial $projectMaterial
    ) {

        /*
        |--------------------------------------------------------------------------
        | GET PROJECT
        |--------------------------------------------------------------------------
        */

        $project = Project::with([
            'projectMaterials.material',
            'projectMaterials.supplier',
        ])
        ->findOrFail(
            $projectMaterial->project_id
        );


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if ($project->status === 'cancelled') {

            return redirect()
                ->route(
                    'admin.project-materials.show',
                    $projectMaterial
                )
                ->with(
                    'error',
                    'Cancelled project materials cannot be edited.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | DROPDOWN DATA
        |--------------------------------------------------------------------------
        */

        $materials = Material::orderBy(
            'material_name'
        )->get();


        $suppliers = Supplier::orderBy(
            'supplier_name'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.project-materials.edit',
            compact(
                'project',
                'materials',
                'suppliers'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     *
     * Update all materials of a project.
     *
     * Handles:
     *
     * 1. Existing material update
     * 2. New material creation
     * 3. Removed material deletion
     */
    public function update(
        Request $request,
        ProjectMaterial $projectMaterial
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

            'materials' => [
                'required',
                'array',
                'min:1',
            ],

            'materials.*.id' => [
                'nullable',
                'integer',
                'exists:project_materials,id',
            ],

            'materials.*.material_id' => [
                'required',
                'exists:materials,id',
            ],

            'materials.*.supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'materials.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

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
        | MAKE SURE URL RECORD BELONGS TO PROJECT
        |--------------------------------------------------------------------------
        */

        if (
            $projectMaterial->project_id
            !=
            $project->id
        ) {

            abort(404);

        }


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT CHECK
        |--------------------------------------------------------------------------
        */

        if ($project->status === 'cancelled') {

            return redirect()
                ->route(
                    'admin.project-materials.show',
                    $projectMaterial
                )
                ->with(
                    'error',
                    'Cancelled project materials cannot be edited.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $project
        ) {

            /*
            |--------------------------------------------------------------------------
            | EXISTING DATABASE RECORD IDS
            |--------------------------------------------------------------------------
            */

            $existingIds =
                $project->projectMaterials()
                    ->pluck('id')
                    ->toArray();


            /*
            |--------------------------------------------------------------------------
            | IDS RECEIVED FROM FORM
            |--------------------------------------------------------------------------
            */

            $submittedIds =
                collect($validated['materials'])
                    ->pluck('id')
                    ->filter()
                    ->map(function ($id) {

                        return (int) $id;

                    })
                    ->toArray();


            /*
            |--------------------------------------------------------------------------
            | FIND REMOVED MATERIALS
            |--------------------------------------------------------------------------
            */

            $idsToDelete =
                array_diff(
                    $existingIds,
                    $submittedIds
                );


            /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED MATERIALS
            |--------------------------------------------------------------------------
            */

            if (!empty($idsToDelete)) {

                ProjectMaterial::whereIn(
                    'id',
                    $idsToDelete
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->delete();

            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE / CREATE
            |--------------------------------------------------------------------------
            */

            foreach (
                $validated['materials']
                as $materialData
            ) {

                /*
                |--------------------------------------------------------------------------
                | GET REAL MATERIAL
                |--------------------------------------------------------------------------
                */

                $material = Material::findOrFail(
                    $materialData['material_id']
                );


                /*
                |--------------------------------------------------------------------------
                | DATABASE PRICE
                |--------------------------------------------------------------------------
                */

                $unitPrice =
                    (float) $material->unit_price;


                /*
                |--------------------------------------------------------------------------
                | QUANTITY
                |--------------------------------------------------------------------------
                */

                $quantity =
                    (float) $materialData['quantity'];


                /*
                |--------------------------------------------------------------------------
                | CALCULATE TOTAL
                |--------------------------------------------------------------------------
                */

                $totalPrice =
                    $quantity * $unitPrice;


                /*
                |--------------------------------------------------------------------------
                | EXISTING RECORD
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($materialData['id'])
                ) {

                    $record =
                        ProjectMaterial::where(
                            'id',
                            $materialData['id']
                        )
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->first();


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    if ($record) {

                        $record->update([

                            'material_id' =>
                                $material->id,

                            'supplier_id' =>
                                $materialData['supplier_id'],

                            'quantity' =>
                                $quantity,

                            'unit_price' =>
                                $unitPrice,

                            'total_price' =>
                                $totalPrice,

                        ]);

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | NEW RECORD
                |--------------------------------------------------------------------------
                */

                else {

                    ProjectMaterial::create([

                        'project_id' =>
                            $project->id,

                        'material_id' =>
                            $material->id,

                        'supplier_id' =>
                            $materialData['supplier_id'],

                        'quantity' =>
                            $quantity,

                        'unit_price' =>
                            $unitPrice,

                        'total_price' =>
                            $totalPrice,

                        'status' =>
                            'active',

                    ]);

                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | GET FIRST MATERIAL AFTER UPDATE
        |--------------------------------------------------------------------------
        */

        $firstMaterial =
            $project->projectMaterials()
                ->first();


        /*
        |--------------------------------------------------------------------------
        | IF NO MATERIALS LEFT
        |--------------------------------------------------------------------------
        */

        if (!$firstMaterial) {

            return redirect()
                ->route(
                    'admin.project-materials.index'
                )
                ->with(
                    'success',
                    'Project materials updated successfully.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO INVOICE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.project-materials.show',
                $firstMaterial
            )
            ->with(
                'success',
                'Project materials updated successfully.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * =========================================================
     *
     * Delete one project material record.
     *
     * Your route uses POST /delete,
     * so this works with your GET + POST only rule.
     */
    public function destroy(
        ProjectMaterial $projectMaterial
    ) {

        /*
        |--------------------------------------------------------------------------
        | GET PROJECT
        |--------------------------------------------------------------------------
        */

        $project =
            $projectMaterial->project;


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $projectMaterial->delete();


        /*
        |--------------------------------------------------------------------------
        | CHECK IF PROJECT STILL HAS MATERIALS
        |--------------------------------------------------------------------------
        */

        $remainingMaterials =
            $project->projectMaterials()->exists();


        /*
        |--------------------------------------------------------------------------
        | IF NO MATERIALS LEFT
        |--------------------------------------------------------------------------
        */

        if (!$remainingMaterials) {

            return redirect()
                ->route(
                    'admin.project-materials.index'
                )
                ->with(
                    'success',
                    'Project material deleted successfully.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | OTHERWISE BACK TO INDEX
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.project-materials.index'
            )
            ->with(
                'success',
                'Project material deleted successfully.'
            );
    }
}