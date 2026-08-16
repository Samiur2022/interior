@extends('backend.layouts.admin')

@section('title', 'Supplier Details')

@section('page_title', 'Supplier Details')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Supplier Details
        </h1>

        <p>
            View complete information about this supplier.
        </p>

    </div>


    <div class="page-header-actions">

        {{-- Edit Supplier --}}
        <a
            href="{{ route('admin.suppliers.edit', $supplier) }}"
            class="primary-btn"
        >
            Edit Supplier
        </a>


        {{-- Back --}}
        <a
            href="{{ route('admin.suppliers.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>

    </div>

</div>



{{-- =====================================================
    SUPPLIER INFORMATION
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Supplier Information
            </h2>

            <p>
                Basic information about the supplier.
            </p>

        </div>

    </div>


    <div class="details-grid">


        {{-- Supplier Name --}}
        <div class="detail-item">

            <span class="detail-label">
                Supplier Name
            </span>

            <strong class="detail-value">
                {{ $supplier->supplier_name }}
            </strong>

        </div>



        {{-- Phone --}}
        <div class="detail-item">

            <span class="detail-label">
                Phone
            </span>

            <strong class="detail-value">

                {{ $supplier->phone ?? 'N/A' }}

            </strong>

        </div>



        {{-- Email --}}
        <div class="detail-item">

            <span class="detail-label">
                Email
            </span>

            <strong class="detail-value">

                {{ $supplier->email }}

            </strong>

        </div>



        {{-- Address --}}
        <div class="detail-item">

            <span class="detail-label">
                Address
            </span>

            <strong class="detail-value">

                {{ $supplier->address ?? 'N/A' }}

            </strong>

        </div>



        {{-- Created At --}}
        <div class="detail-item">

            <span class="detail-label">
                Created At
            </span>

            <strong class="detail-value">

                {{ $supplier->created_at
                    ? $supplier->created_at->format('d M Y, h:i A')
                    : 'N/A'
                }}

            </strong>

        </div>



        {{-- Updated At --}}
        <div class="detail-item">

            <span class="detail-label">
                Last Updated
            </span>

            <strong class="detail-value">

                {{ $supplier->updated_at
                    ? $supplier->updated_at->format('d M Y, h:i A')
                    : 'N/A'
                }}

            </strong>

        </div>


    </div>

</div>



{{-- =====================================================
    PROJECT MATERIAL USAGE
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Materials
            </h2>

            <p>
                Projects associated with this supplier.
            </p>

        </div>

    </div>


    <div class="empty-module">

        <span>
            Project material information will be available
            after the Project Materials module is completed.
        </span>

    </div>

</div>


@endsection