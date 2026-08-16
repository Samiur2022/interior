@extends('backend.layouts.admin')

@section('title', 'Suppliers')

@section('page_title', 'Suppliers')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Suppliers
        </h1>

        <p>
            Manage suppliers and their contact information.
        </p>

    </div>


    <a
        href="{{ route('admin.suppliers.create') }}"
        class="primary-btn"
    >
        + Add Supplier
    </a>

</div>






{{-- =====================================================
    SUPPLIER TABLE
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Supplier List
            </h2>

            <p>
                All suppliers currently available in the system.
            </p>

        </div>


        <span class="table-count">

            {{ $suppliers->count() }} Suppliers

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
                        SUPPLIER NAME
                    </th>

                    <th>
                        PHONE
                    </th>

                    <th>
                        EMAIL
                    </th>

                    <th>
                        ADDRESS
                    </th>

                    <th>
                        ACTIONS
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($suppliers as $supplier)

                    <tr>


                        {{-- Supplier ID --}}
                        <td>

                             {{ $loop->iteration }}

                        </td>



                        {{-- Supplier Name --}}
                        <td>

                            <strong>
                                {{ $supplier->supplier_name }}
                            </strong>

                        </td>



                        {{-- Phone --}}
                        <td>

                            {{ $supplier->phone ?? 'N/A' }}

                        </td>



                        {{-- Email --}}
                        <td>

                            {{ $supplier->email }}

                        </td>



                        {{-- Address --}}
                        <td>

                            {{ $supplier->address ?? 'N/A' }}

                        </td>



                        {{-- Actions --}}
                        <td>

                            <div class="table-actions">


                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.suppliers.show',
                                        $supplier
                                    ) }}"
                                    class="small-action view"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.suppliers.edit',
                                        $supplier
                                    ) }}"
                                    class="small-action edit"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.suppliers.destroy',
                                        $supplier
                                    ) }}"
                                    method="POST"
                                    class="delete-form"
                                    onsubmit="return confirm(
                                        'Are you sure you want to delete this supplier?'
                                    );"
                                >

                                    @csrf

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


                    {{-- =================================================
                        NO SUPPLIERS
                    ================================================== --}}

                    <tr>

                        <td
                            colspan="6"
                            class="empty-state"
                        >

                            <div>

                                <strong>
                                    No suppliers found.
                                </strong>

                                <p>
                                    Add your first supplier to get started.
                                </p>

                                <a
                                    href="{{ route('admin.suppliers.create') }}"
                                    class="primary-btn"
                                >
                                    + Add Supplier
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