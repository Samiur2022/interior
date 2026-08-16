@extends('backend.layouts.admin')


@section('title', 'Edit Client')


@section('page_title', 'Edit Client')


@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Edit Client
        </h1>

        <p>
            Update the client's information.
        </p>

    </div>


    <a
        href="{{ route('admin.clients.show', $client) }}"
        class="secondary-btn"
    >
        ← Back to Client
    </a>

</div>



{{-- =====================================================
    EDIT CLIENT FORM
====================================================== --}}

<div class="panel">


    <div class="panel-header">

        <div>

            <h2>
                Client Information
            </h2>

            <p>
                Update information for
                <strong>{{ $client->name }}</strong>
            </p>

        </div>

    </div>



    <div class="form-container">


        <form
            action="{{ route('admin.clients.update', $client) }}"
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
                    value="{{ old('name', $client->name) }}"
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
                    value="{{ old('phone', $client->phone) }}"
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
                    value="{{ old('email', $client->email) }}"
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
                >{{ old('address', $client->address) }}</textarea>


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
                    href="{{ route('admin.clients.show', $client) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Update Client
                </button>


            </div>


        </form>


    </div>

</div>


@endsection