@extends('backend.layouts.admin')


@section('title', 'Add Client')


@section('page_title', 'Add Client')


@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Client
        </h1>

        <p>
            Add a new client to your interior project system.
        </p>

    </div>


    {{-- Back to client list --}}
    <a
        href="{{ route('admin.clients.index') }}"
        class="secondary-btn"
    >
        ← Back to Clients
    </a>

</div>



{{-- =====================================================
    CLIENT FORM
====================================================== --}}

<div class="panel">


    <div class="panel-header">

        <div>

            <h2>
                Client Information
            </h2>

            <p>
                Enter the client's basic information below.
            </p>

        </div>

    </div>



    <div class="form-container">


        {{--

            IMPORTANT:

            This form sends data to:

            POST /admin/clients

            which is connected to:

            ClientController@store

        --}}

        <form
            action="{{ route('admin.clients.store') }}"
            method="POST"
        >

            @csrf


            {{-- =================================================
                CLIENT NAME
            ================================================== --}}

            <div class="form-group">

                <label for="name">

                    Client Name

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter client full name"
                    required
                >


                @error('name')

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

                    Phone Number

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Enter phone number"
                    required
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

                    Email Address

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email address"
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
                    placeholder="Enter client address"
                >{{ old('address') }}</textarea>


                @error('address')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                FORM BUTTONS
            ================================================== --}}

            <div class="form-actions">


                <a
                    href="{{ route('admin.clients.index') }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Save Client
                </button>


            </div>


        </form>


    </div>

</div>


@endsection