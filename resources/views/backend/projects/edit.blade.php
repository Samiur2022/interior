@extends('backend.layouts.admin')

@section('title', 'Edit Project')

@section('page_title', 'Edit Project')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Edit Project
        </h1>

        <p>
            Update the information of this project.
        </p>

    </div>


    <div class="page-header-actions">

        <a
            href="{{ route('admin.projects.show', $project) }}"
            class="secondary-btn">
            ← Back to Project
        </a>

    </div>

</div>



{{-- =====================================================
    EDIT PROJECT FORM
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Project Information
            </h2>

            <p>
                Update project details below.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.projects.update', $project) }}"
            method="POST">

            @csrf




            {{-- =================================================
                CLIENT
            ================================================== --}}

            <div class="form-group">

                <label for="client_id">

                    Client

                    <span class="required">
                        *
                    </span>

                </label>


                <select
                    id="client_id"
                    name="client_id"
                    required>

                    <option value="">
                        Select Client
                    </option>


                    @foreach($clients as $client)

                    <option
                        value="{{ $client->id }}"
                        {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>

                        {{ $client->name }}

                    </option>

                    @endforeach

                </select>


                @error('client_id')

                <small class="field-error">
                    {{ $message }}
                </small>

                @enderror

            </div>



            {{-- =================================================
                PROJECT NAME
            ================================================== --}}

            <div class="form-group">

                <label for="project_name">

                    Project Name

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    id="project_name"
                    name="project_name"
                    value="{{ old('project_name', $project->project_name) }}"
                    placeholder="Example: Apartment Interior Design"
                    required>


                @error('project_name')

                <small class="field-error">
                    {{ $message }}
                </small>

                @enderror

            </div>



            {{-- =================================================
                LOCATION
            ================================================== --}}

            <div class="form-group">

                <label for="location">

                    Location

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', $project->location) }}"
                    placeholder="Example: Dhanmondi, Dhaka">


                @error('location')

                <small class="field-error">
                    {{ $message }}
                </small>

                @enderror

            </div>



            {{-- =================================================
                DATE ROW
            ================================================== --}}

            <div class="form-row">


                {{-- Start Date --}}
                <div class="form-group">

                    <label for="start_date">

                        Start Date

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                        required>


                    @error('start_date')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                    @enderror

                </div>



                {{-- End Date --}}
                <div class="form-group">

                    <label for="end_date">

                        End Date

                        <span class="optional">
                            Optional
                        </span>

                    </label>


                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">


                    @error('end_date')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                    @enderror

                </div>


            </div>



            {{-- =================================================
                STATUS
            ================================================== --}}

            <div class="form-group">

                <label for="status">

                    Project Status

                    <span class="required">
                        *
                    </span>

                </label>


                <select
                    id="status"
                    name="status"
                    required>

                    <option
                        value="pending"
                        {{ old('status', $project->status) === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>


                    <option
                        value="ongoing"
                        {{ old('status', $project->status) === 'ongoing' ? 'selected' : '' }}>
                        Ongoing
                    </option>


                    <option
                        value="completed"
                        {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>


                    <option
                        value="on-hold"
                        {{ old('status', $project->status) === 'on-hold' ? 'selected' : '' }}>
                        On Hold
                    </option>

                    <option value="cancelled"
                        {{ old('status', $project->status ?? '') === 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>

                </select>


                @error('status')

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
                    href="{{ route('admin.projects.show', $project) }}"
                    class="secondary-btn">
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn">
                    Update Project
                </button>

            </div>


        </form>

    </div>

</div>


@endsection