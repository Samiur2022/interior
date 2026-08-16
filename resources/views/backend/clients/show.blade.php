@extends('backend.layouts.admin')


@section('title', 'Client Details')


@section('page_title', 'Client Details')


@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Client Details
        </h1>

        <p>
            View complete information about this client.
        </p>

    </div>


    <div class="header-actions">

        {{-- Back to clients --}}
        <a
            href="{{ route('admin.clients.index') }}"
            class="secondary-btn"
        >
            ← Back to Clients
        </a>


        {{-- Edit client --}}
        <a
            href="{{ route('admin.clients.edit', $client) }}"
            class="primary-btn"
        >
            Edit Client
        </a>

    </div>

</div>



{{-- =====================================================
    CLIENT PROFILE
====================================================== --}}

<div class="client-details-grid">


    {{-- =================================================
        BASIC INFORMATION
    ================================================== --}}

    <div class="panel">


        <div class="panel-header">

            <div>

                <h2>
                    Basic Information
                </h2>

                <p>
                    Client contact information
                </p>

            </div>

        </div>



        <div class="details-body">


            {{-- Client Name --}}
            <div class="detail-item">

                <span class="detail-label">
                    Client Name
                </span>

                <strong class="detail-value">
                    {{ $client->name }}
                </strong>

            </div>



            {{-- Phone --}}
            <div class="detail-item">

                <span class="detail-label">
                    Phone Number
                </span>

                <strong class="detail-value">
                    {{ $client->phone }}
                </strong>

            </div>



            {{-- Email --}}
            <div class="detail-item">

                <span class="detail-label">
                    Email Address
                </span>

                <strong class="detail-value">

                    {{ $client->email ?? 'Not provided' }}

                </strong>

            </div>



            {{-- Address --}}
            <div class="detail-item">

                <span class="detail-label">
                    Address
                </span>

                <strong class="detail-value">

                    {{ $client->address ?? 'Not provided' }}

                </strong>

            </div>


        </div>

    </div>



    {{-- =================================================
        SYSTEM INFORMATION
    ================================================== --}}

    <div class="panel">


        <div class="panel-header">

            <div>

                <h2>
                    System Information
                </h2>

                <p>
                    Record information
                </p>

            </div>

        </div>



        <div class="details-body">


            {{-- Client ID --}}
            <div class="detail-item">

                <span class="detail-label">
                    Client ID
                </span>

                <strong class="detail-value">
                    #{{ $client->id }}
                </strong>

            </div>



            {{-- Created Date --}}
            <div class="detail-item">

                <span class="detail-label">
                    Created At
                </span>

                <strong class="detail-value">

                    {{ $client->created_at->format('d M Y, h:i A') }}

                </strong>

            </div>



            {{-- Updated Date --}}
            <div class="detail-item">

                <span class="detail-label">
                    Last Updated
                </span>

                <strong class="detail-value">

                    {{ $client->updated_at->format('d M Y, h:i A') }}

                </strong>

            </div>


        </div>

    </div>


</div>



{{-- =====================================================
    DELETE CLIENT
====================================================== --}}

<div class="panel danger-panel">


    <div class="danger-content">


        <div>

            <h3>
                Delete Client
            </h3>

            <p>
                Deleting this client will permanently remove
                their information from the system.
            </p>

        </div>


        <form
            action="{{ route('admin.clients.destroy', $client) }}"
            method="POST"
            class="delete-form"
        >

            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="danger-btn"
            >
                Delete Client
            </button>

        </form>


    </div>

</div>


@endsection