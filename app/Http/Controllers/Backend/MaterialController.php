<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials.
     */
    public function index()
    {
        // Get all materials, newest first
        $materials = Material::latest()->get();

        // Send materials to index view
        return view(
            'backend.materials.index',
            compact('materials')
        );
    }


    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        // Show create material form
        return view('backend.materials.create');
    }


    /**
     * Store a newly created material.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Material Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Material name is required
            'material_name' => 'required|string|max:255',

            // Unit is optional
            'unit' => 'nullable|string|max:100',

            // Unit price is required and must be numeric
            'unit_price' => 'required|numeric|min:0',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Material
        |--------------------------------------------------------------------------
        */

        Material::create($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material created successfully.');
    }


    /**
     * Display the specified material.
     */
    public function show(Material $material)
    {
        // Show material details
        return view(
            'backend.materials.show',
            compact('material')
        );
    }


    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $material)
    {
        // Show edit form with existing material data
        return view(
            'backend.materials.edit',
            compact('material')
        );
    }


    /**
     * Update the specified material.
     */
    public function update(Request $request, Material $material)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Updated Material Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'material_name' => 'required|string|max:255',

            'unit' => 'nullable|string|max:100',

            'unit_price' => 'required|numeric|min:0',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Material
        |--------------------------------------------------------------------------
        */

        $material->update($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.materials.show', $material)
            ->with('success', 'Material updated successfully.');
    }


    /**
     * Remove the specified material.
     */
    public function destroy(Material $material)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Material
        |--------------------------------------------------------------------------
        */

        $material->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}