@extends('backend.layouts.admin')

@section('title', 'Add Progress')

@section('page_title', 'Add Progress')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Project Progress
        </h1>

        <p>
            Add or update progress for a specific work.
        </p>

    </div>


    <a
        href="{{ route('admin.progress-reports.index') }}"
        class="secondary-btn"
    >
        ← Back
    </a>

</div>



{{-- =====================================================
    FORM PANEL
====================================================== --}}

<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                Progress Details
            </h2>

            <p>
                Enter the current work progress.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route('admin.progress-reports.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="progressReportForm"
        >

            @csrf



            {{-- =================================================
                PROJECT
            ================================================== --}}

            <div class="form-group">

                <label for="project_id">

                    Project

                    <span class="required">
                        *
                    </span>

                </label>


                <select
                    name="project_id"
                    id="project_id"
                    required
                >

                    <option value="">
                        -- Select Project --
                    </option>


                    @foreach($projects as $project)

                        <option
                            value="{{ $project->id }}"
                            data-status="{{ $project->status }}"
                            {{ old('project_id') == $project->id
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $project->project_name }}

                        </option>

                    @endforeach

                </select>


                @error('project_id')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror


                {{-- PROJECT STATUS MESSAGE --}}

                <small
                    id="projectStatusMessage"
                    class="form-help"
                    style="display:none;"
                ></small>

            </div>



            {{-- =================================================
                WORK TYPE
            ================================================== --}}

            <div class="form-group">

                <label for="work_type">

                    Work Type

                    <span class="required">
                        *
                    </span>

                </label>


                <input
                    type="text"
                    name="work_type"
                    id="work_type"
                    value="{{ old('work_type') }}"
                    placeholder="Example: Electrical, Carpeting, Painting"
                    maxlength="255"
                    required
                >


                @error('work_type')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                PROGRESS PERCENTAGE
            ================================================== --}}

            <div class="form-group">

                <label for="progress_percent">

                    Progress Percentage

                    <span class="required">
                        *
                    </span>

                </label>


                <div class="progress-input-wrapper">

                    <input
                        type="number"
                        name="progress_percent"
                        id="progress_percent"
                        value="{{ old(
                            'progress_percent',
                            0
                        ) }}"
                        min="0"
                        max="100"
                        step="1"
                        required
                    >


                    <span class="percentage-symbol">
                        %
                    </span>

                </div>


                @error('progress_percent')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror


                <small
                    id="progressMessage"
                    class="form-help"
                >
                    Enter progress between 0% and 100%.
                </small>

            </div>



            {{-- =================================================
                LIVE PROGRESS PREVIEW
            ================================================== --}}

            <div class="progress-preview-card">

                <div class="progress-preview-header">

                    <div>

                        <strong>
                            Work Progress
                        </strong>

                        <span>
                            Current completion
                        </span>

                    </div>


                    <strong id="progressValue">
                        0%
                    </strong>

                </div>


                <div class="progress-preview-bar">

                    <div
                        class="progress-preview-fill"
                        id="progressBar"
                        style="width: 0%;"
                    ></div>

                </div>

            </div>



            {{-- =================================================
                DESCRIPTION
            ================================================== --}}

            <div class="form-group">

                <label for="description">

                    Description

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    placeholder="Describe the current work progress..."
                >{{ old('description') }}</textarea>


                @error('description')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                IMAGE
            ================================================== --}}

            <div class="form-group">

                <label for="image">

                    Progress Image

                    <span class="optional">
                        Optional
                    </span>

                </label>


                <input
                    type="file"
                    name="image"
                    id="image"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                >


                <small class="form-help">

                    JPG, JPEG, PNG or WEBP.
                    Maximum size: 2 MB.

                </small>


                @error('image')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>



            {{-- =================================================
                IMAGE PREVIEW
            ================================================== --}}

            <div
                class="progress-image-preview"
                id="imagePreviewContainer"
                style="display:none;"
            >

                <div class="progress-image-preview-header">

                    <strong>
                        Image Preview
                    </strong>


                    <button
                        type="button"
                        id="removeImage"
                        class="small-action delete"
                    >
                        Remove
                    </button>

                </div>


                <img
                    src=""
                    alt="Progress image preview"
                    id="imagePreview"
                >

            </div>



            {{-- =================================================
                FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route(
                        'admin.progress-reports.index'
                    ) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                    id="submitButton"
                >
                    Save Progress
                </button>

            </div>


        </form>

    </div>

</div>



{{-- =====================================================
    JAVASCRIPT
====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const projectSelect =
        document.getElementById('project_id');

    const workTypeInput =
        document.getElementById('work_type');

    const progressInput =
        document.getElementById('progress_percent');

    const descriptionInput =
        document.getElementById('description');

    const imageInput =
        document.getElementById('image');

    const progressValue =
        document.getElementById('progressValue');

    const progressBar =
        document.getElementById('progressBar');

    const progressMessage =
        document.getElementById('progressMessage');

    const projectStatusMessage =
        document.getElementById(
            'projectStatusMessage'
        );

    const submitButton =
        document.getElementById('submitButton');

    const imagePreview =
        document.getElementById('imagePreview');

    const imagePreviewContainer =
        document.getElementById(
            'imagePreviewContainer'
        );

    const removeImage =
        document.getElementById('removeImage');



    /*
    |--------------------------------------------------------------------------
    | DISABLED PROJECT STATUSES
    |--------------------------------------------------------------------------
    */

    const blockedStatuses = [
        'on-hold',
        'completed',
        'cancelled'
    ];



    /*
    |--------------------------------------------------------------------------
    | PROJECT STATUS CHECK
    |--------------------------------------------------------------------------
    */

    function checkProjectStatus() {

        const selectedOption =
            projectSelect.options[
                projectSelect.selectedIndex
            ];


        /*
        |--------------------------------------------------------------------------
        | NO PROJECT SELECTED
        |--------------------------------------------------------------------------
        */

        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            enableFormFields();

            projectStatusMessage.style.display =
                'none';

            return;

        }


        const status =
            selectedOption.dataset.status;


        /*
        |--------------------------------------------------------------------------
        | BLOCKED PROJECT
        |--------------------------------------------------------------------------
        */

        if (
            blockedStatuses.includes(status)
        ) {

            disableFormFields();

            const readableStatus =
                status
                    .replace('-', ' ')
                    .replace(/\b\w/g, function (letter) {
                        return letter.toUpperCase();
                    });


            projectStatusMessage.textContent =
                `Progress cannot be added because this project is ${readableStatus}.`;

            projectStatusMessage.style.display =
                'block';


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVE PROJECT
        |--------------------------------------------------------------------------
        */

        enableFormFields();

        projectStatusMessage.textContent =
            `This project is ${status}. Progress can be added or updated.`;

        projectStatusMessage.style.display =
            'block';

    }



    /*
    |--------------------------------------------------------------------------
    | DISABLE FORM FIELDS
    |--------------------------------------------------------------------------
    */

    function disableFormFields() {

        workTypeInput.disabled =
            true;

        progressInput.disabled =
            true;

        descriptionInput.disabled =
            true;

        imageInput.disabled =
            true;

        submitButton.disabled =
            true;

        submitButton.textContent =
            'Progress Locked';

    }



    /*
    |--------------------------------------------------------------------------
    | ENABLE FORM FIELDS
    |--------------------------------------------------------------------------
    */

    function enableFormFields() {

        workTypeInput.disabled =
            false;

        progressInput.disabled =
            false;

        descriptionInput.disabled =
            false;

        imageInput.disabled =
            false;

        submitButton.disabled =
            false;

        submitButton.textContent =
            'Save Progress';

    }



    /*
    |--------------------------------------------------------------------------
    | PROJECT CHANGE
    |--------------------------------------------------------------------------
    */

    projectSelect.addEventListener(
        'change',
        function () {

            checkProjectStatus();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | PROGRESS PREVIEW
    |--------------------------------------------------------------------------
    */

    function updateProgressPreview() {

        let progress =
            parseInt(
                progressInput.value
            ) || 0;


        if (progress < 0) {

            progress = 0;

        }


        if (progress > 100) {

            progress = 100;

        }


        progressValue.textContent =
            `${progress}%`;


        progressBar.style.width =
            `${progress}%`;


        if (progress === 100) {

            progressMessage.textContent =
                'This work is completed.';

        }
        else if (progress > 0) {

            progressMessage.textContent =
                `Current work progress: ${progress}%.`;

        }
        else {

            progressMessage.textContent =
                'Enter progress between 0% and 100%.';

        }

    }



    /*
    |--------------------------------------------------------------------------
    | PROGRESS INPUT
    |--------------------------------------------------------------------------
    */

    progressInput.addEventListener(
        'input',
        updateProgressPreview
    );



    /*
    |--------------------------------------------------------------------------
    | IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    imageInput.addEventListener(
        'change',
        function (event) {

            const file =
                event.target.files[0];


            if (!file) {

                imagePreviewContainer.style.display =
                    'none';

                imagePreview.src =
                    '';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | FILE TYPE
            |--------------------------------------------------------------------------
            */

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];


            if (
                !allowedTypes.includes(
                    file.type
                )
            ) {

                alert(
                    'Please select a JPG, JPEG, PNG or WEBP image.'
                );

                imageInput.value =
                    '';

                imagePreviewContainer.style.display =
                    'none';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | FILE SIZE
            |--------------------------------------------------------------------------
            */

            const maxSize =
                2 * 1024 * 1024;


            if (
                file.size > maxSize
            ) {

                alert(
                    'Image size cannot exceed 2 MB.'
                );

                imageInput.value =
                    '';

                imagePreviewContainer.style.display =
                    'none';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | IMAGE PREVIEW
            |--------------------------------------------------------------------------
            */

            const reader =
                new FileReader();


            reader.onload =
                function (e) {

                    imagePreview.src =
                        e.target.result;

                    imagePreviewContainer.style.display =
                        'block';

                };


            reader.readAsDataURL(file);

        }
    );



    /*
    |--------------------------------------------------------------------------
    | REMOVE IMAGE
    |--------------------------------------------------------------------------
    */

    removeImage.addEventListener(
        'click',
        function () {

            imageInput.value =
                '';

            imagePreview.src =
                '';

            imagePreviewContainer.style.display =
                'none';

        }
    );



    /*
    |--------------------------------------------------------------------------
    | INITIAL STATE
    |--------------------------------------------------------------------------
    */

    updateProgressPreview();

    checkProjectStatus();

});

</script>


@endsection