@extends('backend.layouts.admin')

@section('title', 'Material Details')

@section('page_title', 'Material Details')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Material Details
        </h1>

        <p>
            View complete information about this material.
        </p>

    </div>


    <div class="page-header-actions">

        {{-- Edit Material --}}
        <a
            href="{{ route('admin.materials.edit', $material) }}"
            class="primary-btn"
        >
            Edit Material
        </a>


        {{-- Back to Materials --}}
        <a
            href="{{ route('admin.materials.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>

    </div>

</div>



{{-- =====================================================
    MATERIAL INFORMATION
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Material Information
            </h2>

            <p>
                Basic information about the material.
            </p>

        </div>

    </div>



    <div class="details-grid">


        {{-- Material ID --}}
        <div class="detail-item">

            <span class="detail-label">
                Material ID
            </span>

            <strong class="detail-value">
                #{{ $material->id }}
            </strong>

        </div>



        {{-- Material Name --}}
        <div class="detail-item">

            <span class="detail-label">
                Material Name
            </span>

            <strong class="detail-value">
                {{ $material->material_name }}
            </strong>

        </div>



        {{-- Unit --}}
        <div class="detail-item">

            <span class="detail-label">
                Unit
            </span>

            <strong class="detail-value">

                {{ $material->unit ?? 'N/A' }}

            </strong>

        </div>



        {{-- Unit Price --}}
        <div class="detail-item">

            <span class="detail-label">
                Unit Price
            </span>

            <strong class="detail-value">

                {{ number_format(
                    (float) $material->unit_price,
                    2
                ) }}

            </strong>

        </div>



        {{-- Created At --}}
        <div class="detail-item">

            <span class="detail-label">
                Created At
            </span>

            <strong class="detail-value">

                {{ $material->created_at
                    ? $material->created_at->format('d M Y, h:i A')
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

                {{ $material->updated_at
                    ? $material->updated_at->format('d M Y, h:i A')
                    : 'N/A'
                }}

            </strong>

        </div>


    </div>

</div>



{{-- =====================================================
    PROJECT USAGE
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Usage
            </h2>

            <p>
                Projects where this material is used.
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