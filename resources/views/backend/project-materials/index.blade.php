@extends('backend.layouts.admin')

@section('title', 'Project Materials')

@section('page_title', 'Project Materials')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Project Materials
        </h1>

        <p>
            View materials and total costs for each project.
        </p>

    </div>


    <a
        href="{{ route('admin.project-materials.create') }}"
        class="primary-btn"
    >
        + Add Project Material
    </a>

</div>



{{-- =====================================================
    PROJECT MATERIAL SUMMARY
====================================================== --}}

<div class="panel">


    {{-- =================================================
        PANEL HEADER
    ================================================== --}}

    <div class="panel-header">

        <div>

            <h2>
                Project Material List
            </h2>

            <p>
                Each project is shown with its total material cost.
            </p>

        </div>


        <span class="table-count">

            {{ $projects->count() }}

            {{ $projects->count() === 1 ? 'Project' : 'Projects' }}

        </span>

    </div>



    {{-- =================================================
        TABLE
    ================================================== --}}

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    {{-- Serial --}}

                    <th>
                        #
                    </th>


                    {{-- Project --}}

                    <th>
                        PROJECT
                    </th>


                    {{-- End Date --}}

                    <th>
                        END DATE
                    </th>


                    {{-- Total Price --}}

                    <th>
                        TOTAL PRICE
                    </th>


                    {{-- Status --}}

                    <th>
                        STATUS
                    </th>


                    {{-- Actions --}}

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>



            <tbody>


                @forelse($projects as $project)


                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | PROJECT MATERIAL TOTAL
                        |--------------------------------------------------------------------------
                        */

                        $projectTotal =
                            $project->projectMaterials
                                ->sum('total_price');


                        /*
                        |--------------------------------------------------------------------------
                        | FIRST PROJECT MATERIAL
                        |--------------------------------------------------------------------------
                        |
                        | Used for View/Edit route because the
                        | Controller identifies the project through
                        | a ProjectMaterial record.
                        |
                        */

                        $firstMaterial =
                            $project->projectMaterials->first();


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS CLASS
                        |--------------------------------------------------------------------------
                        */

                        $statusClass = match($project->status) {

                            'pending' =>
                                'status-warning',

                            'ongoing' =>
                                'status-info',

                            'on-hold' =>
                                'status-danger',

                            'completed' =>
                                'status-success',

                            'cancelled' =>
                                'status-danger',

                            default =>
                                'status-secondary',

                        };

                    @endphp



                    <tr>


                        {{-- =================================================
                            SERIAL
                        ================================================== --}}

                        <td>

                            {{ $loop->iteration }}

                        </td>



                        {{-- =================================================
                            PROJECT NAME
                        ================================================== --}}

                        <td>

                            <strong>

                                {{ $project->project_name }}

                            </strong>

                        </td>



                        {{-- =================================================
                            END DATE
                        ================================================== --}}

                        <td>

                            @if($project->end_date)

                                {{ \Carbon\Carbon::parse(
                                    $project->end_date
                                )->format('d M Y') }}

                            @else

                                <span class="text-muted">
                                    N/A
                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                            TOTAL PRICE
                        ================================================== --}}

                        <td>

                            <strong>

                                ৳{{ number_format(
                                    (float) $projectTotal,
                                    2
                                ) }}

                            </strong>

                        </td>



                        {{-- =================================================
                            PROJECT STATUS
                        ================================================== --}}

                        <td>

                            <span
                                class="status-badge {{ $statusClass }}"
                            >

                                {{ ucfirst(
                                    str_replace(
                                        '-',
                                        ' ',
                                        $project->status
                                    )
                                ) }}

                            </span>

                        </td>



                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}

                        <td>

                            <div class="table-actions">


                                {{-- =================================================
                                    VIEW
                                ================================================== --}}

                                @if($firstMaterial)

                                    <a
                                        href="{{ route(
                                            'admin.project-materials.show',
                                            $firstMaterial
                                        ) }}"
                                        class="small-action view"
                                    >
                                        View
                                    </a>

                                @endif



                                {{-- =================================================
                                    EDIT
                                ================================================== --}}

                                @if(
                                    $firstMaterial &&
                                    $project->status !== 'cancelled'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.project-materials.edit',
                                            $firstMaterial
                                        ) }}"
                                        class="small-action edit"
                                    >
                                        Edit
                                    </a>

                                @endif



                                {{-- =================================================
                                    CANCELLED LABEL
                                ================================================== --}}

                                @if(
                                    $project->status === 'cancelled'
                                )

                                    <span
                                        class="small-action disabled-action"
                                    >
                                        Cancelled
                                    </span>

                                @endif


                            </div>

                        </td>


                    </tr>


                @empty


                    {{-- =================================================
                        EMPTY STATE
                    ================================================== --}}

                    <tr>

                        <td
                            colspan="6"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No project materials found.
                                </strong>

                                <p>
                                    Add materials to a project
                                    to see them here.
                                </p>


                                <a
                                    href="{{ route(
                                        'admin.project-materials.create'
                                    ) }}"
                                    class="primary-btn"
                                >
                                    + Add Project Material
                                </a>

                            </div>

                        </td>

                    </tr>


                @endforelse


            </tbody>

        </table>

    </div>

</div>


@endsection