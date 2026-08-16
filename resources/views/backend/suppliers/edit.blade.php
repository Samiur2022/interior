@extends('backend.layouts.admin')

@section('title', 'Edit Supplier')

@section('page_title', 'Edit Supplier')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Edit Supplier
        </h1>

        <p>
            Update the information of this supplier.
        </p>

    </div>


    <div class="page-header-actions">

        <a
            href="{{ route('admin.suppliers.show', $supplier) }}"
            class="secondary-btn"
        >
            ← Back to Supplier
        </a>

    </div>

</div>



{{-- =====================================================
    EDIT SUPPLIER FORM
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Supplier Information
            </h2>

            <p>
                Update supplier details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.suppliers.update', $supplier) }}"
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
                    value="{{ old('supplier_name', $supplier->supplier_name) }}"
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
                    value="{{ old('phone', $supplier->phone) }}"
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
                    value="{{ old('email', $supplier->email) }}"
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
                >{{ old('address', $supplier->address) }}</textarea>


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
                    href="{{ route('admin.suppliers.show', $supplier) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Update Supplier
                </button>

            </div>


        </form>

    </div>

</div>


@endsection