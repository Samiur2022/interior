@extends('backend.layouts.admin')

@section('title', 'Add Supplier')

@section('page_title', 'Add Supplier')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Supplier
        </h1>

        <p>
            Add a new supplier to the system.
        </p>

    </div>


    <a
        href="{{ route('admin.suppliers.index') }}"
        class="secondary-btn"
    >
        ← Back to Suppliers
    </a>

</div>



{{-- =====================================================
    SUPPLIER FORM
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Supplier Information
            </h2>

            <p>
                Enter the supplier details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.suppliers.store') }}"
            method="POST"
        >

            @csrf


            {{-- =================================================
                SUPPLIER NAME
            ================================================== --}}

            <div class="form-group">

                <label for="supplier_name">

                    Supplier Name

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    id="supplier_name"
                    name="supplier_name"
                    value="{{ old('supplier_name') }}"
                    placeholder="Example: ABC Interior Suppliers"
                    required
                >


                @error('supplier_name')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                PHONE
            ================================================== --}}

            <div class="form-group">

                <label for="phone">

                    Phone

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Example: 017XXXXXXXX"
                >


                @error('phone')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                EMAIL
            ================================================== --}}

            <div class="form-group">

                <label for="email">

                    Email

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Example: supplier@example.com"
                    required
                >


                @error('email')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                ADDRESS
            ================================================== --}}

            <div class="form-group">

                <label for="address">

                    Address

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <textarea
                    id="address"
                    name="address"
                    rows="4"
                    placeholder="Enter supplier address"
                >{{ old('address') }}</textarea>


                @error('address')

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
                    href="{{ route('admin.suppliers.index') }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Save Supplier
                </button>

            </div>


        </form>

    </div>

</div>


@endsection