@extends('backend.layouts.admin')

@section('title', 'Edit Material')

@section('page_title', 'Edit Material')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Edit Material
        </h1>

        <p>
            Update the information of this material.
        </p>

    </div>


    <div class="page-header-actions">

        <a
            href="{{ route('admin.materials.show', $material) }}"
            class="secondary-btn"
        >
            ← Back to Material
        </a>

    </div>

</div>



{{-- =====================================================
    EDIT MATERIAL FORM
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Material Information
            </h2>

            <p>
                Update material details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.materials.update', $material) }}"
            method="POST"
        >

            @csrf

           


            {{-- =================================================
                MATERIAL NAME
            ================================================== --}}

            <div class="form-group">

                <label for="material_name">

                    Material Name

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    id="material_name"
                    name="material_name"
                    value="{{ old('material_name', $material->material_name) }}"
                    placeholder="Example: Cement"
                    required
                >


                @error('material_name')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                UNIT
            ================================================== --}}

            <div class="form-group">

                <label for="unit">

                    Unit

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <input
                    type="text"
                    id="unit"
                    name="unit"
                    value="{{ old('unit', $material->unit) }}"
                    placeholder="Example: Bag, Kg, Liter, Feet"
                >


                @error('unit')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                UNIT PRICE
            ================================================== --}}

            <div class="form-group">

                <label for="unit_price">

                    Unit Price

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="number"
                    id="unit_price"
                    name="unit_price"
                    value="{{ old('unit_price', $material->unit_price) }}"
                    placeholder="Example: 550.00"
                    min="0"
                    step="0.01"
                    required
                >


                @error('unit_price')

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
                    href="{{ route('admin.materials.show', $material) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Update Material
                </button>

            </div>


        </form>

    </div>

</div>


@endsection