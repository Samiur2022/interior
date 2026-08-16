@extends('backend.layouts.admin')


@section('title', 'Clients')


@section('page_title', 'Clients')


@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">


    <div>

        <h1>
            Clients
        </h1>

        <p>
            Manage your interior project clients.
        </p>

    </div>


    {{-- Add Client Button --}}
    <a
        href="{{ route('admin.clients.create') }}"
        class="primary-btn"
    >

        + Add Client

    </a>


</div>



{{-- =====================================================
    CLIENT TABLE
====================================================== --}}

<div class="panel">


    {{-- Panel Header --}}
    <div class="panel-header">

        <div>

            <h2>
                All Clients
            </h2>

            <p>
                List of registered clients
            </p>

        </div>

    </div>



    {{-- Table --}}
    <div class="table-wrapper">

        <table>


            <thead>

                <tr>

                    <th>
                        CLIENT
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
                        ACTION
                    </th>

                </tr>

            </thead>



            <tbody>


                {{-- Check if clients exist --}}
                @forelse($clients as $client)


                    <tr>


                        {{-- Client Name --}}
                        <td>

                            <div class="project-name">

                                {{ $client->name }}

                            </div>

                            <small>

                                Client #{{ $loop->iteration }}

                            </small>

                        </td>


                        {{-- Phone --}}
                        <td>

                            {{ $client->phone }}

                        </td>


                        {{-- Email --}}
                        <td>

                            {{ $client->email ?? 'N/A' }}

                        </td>


                        {{-- Address --}}
                        <td>

                            {{ $client->address ?? 'N/A' }}

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="table-actions">


                                {{-- View --}}
                                <a
                                    href="{{ route('admin.clients.show', $client) }}"
                                    class="small-action view"
                                    title="View Client"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.clients.edit', $client) }}"
                                    class="small-action edit"
                                    title="Edit Client"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('admin.clients.destroy', $client) }}"
                                    method="POST"
                                    class="delete-form"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="small-action delete"
                                        title="Delete Client"
                                    >
                                        Delete
                                    </button>

                                </form>


                            </div>

                        </td>


                    </tr>


                @empty


                    {{-- No client found --}}
                    <tr>

                        <td
                            colspan="5"
                            class="empty-state"
                        >

                            <div class="empty-icon">
                                ♙
                            </div>

                            <h3>
                                No Clients Found
                            </h3>

                            <p>
                                You haven't added any clients yet.
                            </p>

                            <a
                                href="{{ route('admin.clients.create') }}"
                                class="primary-btn"
                            >
                                + Add First Client
                            </a>

                        </td>

                    </tr>


                @endforelse


            </tbody>


        </table>

    </div>

</div>


@endsection