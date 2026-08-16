@extends('backend.layouts.admin')

@section('title', 'Add Material')

@section('page_title', 'Add Material')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Material
        </h1>

        <p>
            Add a new material to the system.
        </p>

    </div>


    <a
        href="{{ route('admin.materials.index') }}"
        class="secondary-btn"
    >
        ← Back to Materials
    </a>

</div>



{{-- =====================================================
    MATERIAL FORM
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Material Information
            </h2>

            <p>
                Enter the material details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.materials.store') }}"
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
                    value="{{ old('material_name') }}"
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
                    value="{{ old('unit') }}"
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
                    value="{{ old('unit_price') }}"
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
                    href="{{ route('admin.materials.index') }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Save Material
                </button>

            </div>


        </form>

    </div>

</div>


@endsection