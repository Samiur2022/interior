<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display all suppliers.
     */
    public function index()
    {
        // Get all suppliers, newest first
        $suppliers = Supplier::latest()->get();

        // Send suppliers to index view
        return view(
            'backend.suppliers.index',
            compact('suppliers')
        );
    }


    /**
     * Show create supplier form.
     */
    public function create()
    {
        // Show create form
        return view('backend.suppliers.create');
    }


    /**
     * Store a new supplier.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Supplier Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Supplier name is required
            'supplier_name' => 'required|string|max:255',

            // Phone is optional
            'phone' => 'nullable|string|max:50',

            // Email is required and must be unique
            'email' => 'required|email|unique:suppliers,email',

            // Address is optional
            'address' => 'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Supplier
        |--------------------------------------------------------------------------
        */

        Supplier::create($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }


    /**
     * Display one supplier.
     */
    public function show(Supplier $supplier)
    {
        // Show supplier details
        return view(
            'backend.suppliers.show',
            compact('supplier')
        );
    }


    /**
     * Show edit supplier form.
     */
    public function edit(Supplier $supplier)
    {
        // Show edit form with existing supplier data
        return view(
            'backend.suppliers.edit',
            compact('supplier')
        );
    }


    /**
     * Update existing supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Updated Supplier Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'supplier_name' => 'required|string|max:255',

            'phone' => 'nullable|string|max:50',

            /*
            | Ignore the current supplier's email when checking uniqueness.
            */
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,

            'address' => 'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Supplier
        |--------------------------------------------------------------------------
        */

        $supplier->update($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.suppliers.show', $supplier)
            ->with('success', 'Supplier updated successfully.');
    }


    /**
     * Delete supplier.
     */
    public function destroy(Supplier $supplier)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Supplier
        |--------------------------------------------------------------------------
        */

        $supplier->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}