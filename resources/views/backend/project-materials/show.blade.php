@extends('backend.layouts.admin')

@section('title', 'Material Invoice')

@section('page_title', 'Material Invoice')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Material Invoice
        </h1>

        <p>
            Complete material cost details for this project.
        </p>

    </div>


    <div class="page-header-actions">

        <a
            href="{{ route('admin.project-materials.index') }}"
            class="secondary-btn"
        >
            ← Back
        </a>

    </div>

</div>



{{-- =====================================================
    INVOICE
====================================================== --}}

<div class="invoice-container">


    {{-- =================================================
        INVOICE HEADER
    ================================================== --}}

    <div class="invoice-header">

        <div>

            <h2>
                INTERIOR PMS
            </h2>

            <p>
                Interior Project Management System
            </p>

        </div>


        <div class="invoice-title">

            <strong>
                MATERIAL INVOICE
            </strong>

            <span>
                {{ now()->format('d M Y') }}
            </span>

        </div>

    </div>



    {{-- =================================================
        CLIENT + PROJECT INFORMATION
    ================================================== --}}

    <div class="invoice-info-grid">


        {{-- Client --}}

        <div class="invoice-info-box">

            <span class="invoice-label">
                CLIENT
            </span>

            <strong>

                {{ $project->client
                    ? $project->client->name
                    : 'N/A'
                }}

            </strong>


            @if($project->client)

                @if($project->client->address)

                    <span>
                        {{ $project->client->address }}
                    </span>

                @endif

            @endif

        </div>



        {{-- Project --}}

        <div class="invoice-info-box">

            <span class="invoice-label">
                PROJECT
            </span>

            <strong>
                {{ $project->project_name }}
            </strong>


            @if($project->location)

                <span>
                    {{ $project->location }}
                </span>

            @endif

        </div>



        {{-- End Date --}}

        <div class="invoice-info-box">

            <span class="invoice-label">
                PROJECT END DATE
            </span>


            <strong>

                @if($project->end_date)

                    {{ \Carbon\Carbon::parse(
                        $project->end_date
                    )->format('d M Y') }}

                @else

                    N/A

                @endif

            </strong>

        </div>


    </div>



    {{-- =================================================
        MATERIAL TABLE
    ================================================== --}}

    <div class="invoice-table-wrapper">

        <table class="invoice-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        MATERIAL
                    </th>

                    <th>
                        SUPPLIER
                    </th>

                    <th>
                        QUANTITY
                    </th>

                    <th>
                        UNIT PRICE
                    </th>

                    <th>
                        TOTAL
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach(
                    $project->projectMaterials
                    as $projectMaterial
                )

                    <tr>


                        {{-- Serial --}}

                        <td>

                            {{ $loop->iteration }}

                        </td>



                        {{-- Material --}}

                        <td>

                            <strong>

                                {{ $projectMaterial->material
                                    ? $projectMaterial->material->material_name
                                    : 'N/A'
                                }}

                            </strong>

                        </td>



                        {{-- Supplier --}}

                        <td>

                            {{ $projectMaterial->supplier
                                ? $projectMaterial->supplier->supplier_name
                                : 'N/A'
                            }}

                        </td>



                        {{-- Quantity --}}

                        <td>

                            {{ number_format(
                                (float) $projectMaterial->quantity,
                                2
                            ) }}

                            @if(
                                $projectMaterial->material &&
                                $projectMaterial->material->unit
                            )

                                {{ $projectMaterial->material->unit }}

                            @endif

                        </td>



                        {{-- Unit Price --}}

                        <td>

                            ৳{{ number_format(
                                (float) $projectMaterial->unit_price,
                                2
                            ) }}

                        </td>



                        {{-- Total --}}

                        <td>

                            <strong>

                                ৳{{ number_format(
                                    (float) $projectMaterial->total_price,
                                    2
                                ) }}

                            </strong>

                        </td>


                    </tr>

                @endforeach

            </tbody>


            {{-- =================================================
                GRAND TOTAL
            ================================================== --}}

            <tfoot>

                <tr>

                    <td
                        colspan="5"
                        class="grand-total-label"
                    >

                        GRAND TOTAL

                    </td>


                    <td class="grand-total-value">

                        ৳{{ number_format(
                            (float) $grandTotal,
                            2
                        ) }}

                    </td>

                </tr>

            </tfoot>

        </table>

    </div>



    {{-- =================================================
        FOOTER
    ================================================== --}}

    <div class="invoice-footer">

        <p>
            This document represents the material cost
            summary for the selected project.
        </p>

        <strong>
            Interior PMS
        </strong>

    </div>


</div>


@endsection