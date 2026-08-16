@extends('backend.layouts.admin')

@section('title', 'Materials')

@section('page_title', 'Materials')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Materials
        </h1>

        <p>
            Manage materials and their unit prices.
        </p>

    </div>


    <a
        href="{{ route('admin.materials.create') }}"
        class="primary-btn"
    >
        + Add Material
    </a>

</div>







{{-- =====================================================
    MATERIAL TABLE
====================================================== --}}

<div class="panel">


    <div class="panel-header">

        <div>

            <h2>
                Material List
            </h2>

            <p>
                All materials currently available in the system.
            </p>

        </div>


        <span class="table-count">

            {{ $materials->count() }} Materials

        </span>

    </div>



    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        MATERIAL NAME
                    </th>

                    <th>
                        UNIT
                    </th>

                    <th>
                        UNIT PRICE
                    </th>

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($materials as $material)

                    <tr>


                        {{-- ID --}}
                        <td>

                            {{ $loop->iteration }}

                        </td>



                        {{-- Material Name --}}
                        <td>

                            <strong>
                                {{ $material->material_name }}
                            </strong>

                        </td>



                        {{-- Unit --}}
                        <td>

                            {{ $material->unit ?? 'N/A' }}

                        </td>



                        {{-- Unit Price --}}
                        <td>

                            <strong>

                                {{ number_format(
                                    (float) $material->unit_price,
                                    2
                                ) }}

                            </strong>

                        </td>



                        {{-- Actions --}}
                        <td>

                            <div class="table-actions">


                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.materials.show',
                                        $material
                                    ) }}"
                                    class="small-action view"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.materials.edit',
                                        $material
                                    ) }}"
                                    class="small-action edit"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.materials.destroy',
                                        $material
                                    ) }}"
                                    method="POST"
                                    class="delete-form"
                                    onsubmit="return confirm(
                                        'Are you sure you want to delete this material?'
                                    );"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="small-action delete"
                                    >
                                        Delete
                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>

                @empty


                    {{-- No Materials --}}
                    <tr>

                        <td
                            colspan="5"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No materials found.
                                </strong>

                                <p>
                                    Add your first material to get started.
                                </p>

                                <a
                                    href="{{ route('admin.materials.create') }}"
                                    class="primary-btn"
                                >
                                    + Add Material
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