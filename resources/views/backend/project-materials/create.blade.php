@extends('backend.layouts.admin')

@section('title', 'Add Project Materials')

@section('page_title', 'Add Project Materials')

@section('content')


{{-- =====================================================
    PAGE HEADER
====================================================== --}}

<div class="page-header">

    <div>

        <h1>
            Add Project Materials
        </h1>

        <p>
            Add all materials required for this project.
        </p>

    </div>


    <a
        href="{{ route('admin.project-materials.index') }}"
        class="secondary-btn"
    >
        ← Back
    </a>

</div>



{{-- =====================================================
    MAIN PANEL
====================================================== --}}

<div class="panel">


    {{-- =================================================
        PANEL HEADER
    ================================================== --}}

    <div class="panel-header">

        <div>

            <h2>
                Project Material Information
            </h2>

            <p>
                Select a project and add all required materials.
            </p>

        </div>

    </div>



    {{-- =================================================
        FORM
    ================================================== --}}

    <div class="form-container">

        <form
            action="{{ route('admin.project-materials.store') }}"
            method="POST"
            id="projectMaterialForm"
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
                            {{ old('project_id') == $project->id ? 'selected' : '' }}
                        >

                            {{ $project->project_name }}

                            —
                            {{ ucfirst(
                                str_replace(
                                    '-',
                                    ' ',
                                    $project->status
                                )
                            ) }}

                        </option>

                    @endforeach

                </select>


                @error('project_id')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror


                <small
                    id="projectStatusMessage"
                    class="form-help"
                ></small>

            </div>



            {{-- =================================================
                MATERIAL SECTION
            ================================================== --}}

            <div class="material-section">


                {{-- =================================================
                    SECTION HEADER
                ================================================== --}}

                <div class="material-section-header">

                    <div>

                        <h3>
                            Materials
                        </h3>

                        <p>
                            Add all materials required for this project.
                        </p>

                    </div>


                    <button
                        type="button"
                        class="primary-btn"
                        id="addMaterialButton"
                    >
                        + Add Material
                    </button>

                </div>



                {{-- =================================================
                    MATERIAL TABLE
                ================================================== --}}

                <div class="table-wrapper">

                    <table
                        class="material-entry-table"
                        id="materialTable"
                    >

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

                                <th>
                                    ACTION
                                </th>

                            </tr>

                        </thead>


                        <tbody id="materialRows">

                            {{-- JavaScript adds rows here --}}

                        </tbody>


                        <tfoot>

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align: right;"
                                >

                                    <strong>
                                        GRAND TOTAL
                                    </strong>

                                </td>


                                <td>

                                    <strong id="grandTotal">
                                        ৳0.00
                                    </strong>

                                </td>


                                <td></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>



            {{-- =================================================
                FORM ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route(
                        'admin.project-materials.index'
                    ) }}"
                    class="secondary-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="primary-btn"
                    id="saveButton"
                >
                    Save Project Materials
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
    | DATA FROM LARAVEL
    |--------------------------------------------------------------------------
    */

    const materials = @json($materials);

    const suppliers = @json($suppliers);

    const projects = @json($projects);



    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const projectSelect =
        document.getElementById('project_id');

    const materialRows =
        document.getElementById('materialRows');

    const addMaterialButton =
        document.getElementById('addMaterialButton');

    const grandTotal =
        document.getElementById('grandTotal');

    const saveButton =
        document.getElementById('saveButton');

    const projectStatusMessage =
        document.getElementById(
            'projectStatusMessage'
        );



    /*
    |--------------------------------------------------------------------------
    | ROW COUNTER
    |--------------------------------------------------------------------------
    */

    let rowNumber = 0;



    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    |
    | Prevents material/supplier names from breaking
    | generated HTML.
    |
    */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }



    /*
    |--------------------------------------------------------------------------
    | MATERIAL OPTIONS
    |--------------------------------------------------------------------------
    */

    function getMaterialOptions() {

        let options = `
            <option value="">
                -- Select Material --
            </option>
        `;


        materials.forEach(function (material) {

            options += `
                <option
                    value="${material.id}"
                    data-price="${material.unit_price}"
                    data-unit="${material.unit ?? ''}"
                >
                    ${escapeHtml(material.material_name)}
                </option>
            `;

        });


        return options;

    }



    /*
    |--------------------------------------------------------------------------
    | SUPPLIER OPTIONS
    |--------------------------------------------------------------------------
    */

    function getSupplierOptions() {

        let options = `
            <option value="">
                -- Select Supplier --
            </option>
        `;


        suppliers.forEach(function (supplier) {

            options += `
                <option
                    value="${supplier.id}"
                >
                    ${escapeHtml(
                        supplier.supplier_name
                    )}
                </option>
            `;

        });


        return options;

    }



    /*
    |--------------------------------------------------------------------------
    | ADD MATERIAL ROW
    |--------------------------------------------------------------------------
    */

    function addMaterialRow() {

        rowNumber++;


        const row =
            document.createElement('tr');


        row.classList.add(
            'material-row'
        );


        row.dataset.row =
            rowNumber;



        /*
        |--------------------------------------------------------------------------
        | ROW HTML
        |--------------------------------------------------------------------------
        */

        row.innerHTML = `

            <td class="material-serial">
                ${rowNumber}
            </td>


            <td>

                <select
                    name="materials[${rowNumber}][material_id]"
                    class="material-select"
                    required
                >

                    ${getMaterialOptions()}

                </select>


                <small class="material-unit"></small>

            </td>


            <td>

                <select
                    name="materials[${rowNumber}][supplier_id]"
                    class="supplier-select"
                    required
                >

                    ${getSupplierOptions()}

                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="materials[${rowNumber}][quantity]"
                    class="quantity-input"
                    min="0.01"
                    step="0.01"
                    placeholder="0"
                    required
                >

            </td>


            <td>

                <input
                    type="number"
                    name="materials[${rowNumber}][unit_price]"
                    class="unit-price-input"
                    min="0"
                    step="0.01"
                    readonly
                >

            </td>


            <td>

                <strong class="row-total">
                    ৳0.00
                </strong>

            </td>


            <td>

                <button
                    type="button"
                    class="small-action delete remove-material"
                >
                    Remove
                </button>

            </td>

        `;



        /*
        |--------------------------------------------------------------------------
        | ADD ROW
        |--------------------------------------------------------------------------
        */

        materialRows.appendChild(row);


        /*
        |--------------------------------------------------------------------------
        | INITIALIZE ROW
        |--------------------------------------------------------------------------
        */

        setupRow(row);


        /*
        |--------------------------------------------------------------------------
        | UPDATE SERIAL
        |--------------------------------------------------------------------------
        */

        renumberRows();

    }



    /*
    |--------------------------------------------------------------------------
    | SETUP ROW EVENTS
    |--------------------------------------------------------------------------
    */

    function setupRow(row) {


        const materialSelect =
            row.querySelector(
                '.material-select'
            );


        const quantityInput =
            row.querySelector(
                '.quantity-input'
            );


        const unitPriceInput =
            row.querySelector(
                '.unit-price-input'
            );


        const materialUnit =
            row.querySelector(
                '.material-unit'
            );


        const removeButton =
            row.querySelector(
                '.remove-material'
            );



        /*
        |--------------------------------------------------------------------------
        | MATERIAL CHANGE
        |--------------------------------------------------------------------------
        */

        materialSelect.addEventListener(
            'change',
            function () {


                const selectedOption =
                    materialSelect.options[
                        materialSelect.selectedIndex
                    ];


                /*
                |--------------------------------------------------------------------------
                | NO MATERIAL SELECTED
                |--------------------------------------------------------------------------
                */

                if (!selectedOption.value) {

                    unitPriceInput.value = '';

                    materialUnit.textContent = '';

                    calculateRow(row);

                    return;

                }



                /*
                |--------------------------------------------------------------------------
                | GET DATABASE PRICE SENT TO BLADE
                |--------------------------------------------------------------------------
                */

                const price =
                    parseFloat(
                        selectedOption.dataset.price
                    ) || 0;



                /*
                |--------------------------------------------------------------------------
                | GET UNIT
                |--------------------------------------------------------------------------
                */

                const unit =
                    selectedOption.dataset.unit
                    || '';



                /*
                |--------------------------------------------------------------------------
                | SHOW PRICE
                |--------------------------------------------------------------------------
                */

                unitPriceInput.value =
                    price.toFixed(2);



                /*
                |--------------------------------------------------------------------------
                | SHOW UNIT
                |--------------------------------------------------------------------------
                */

                materialUnit.textContent =
                    unit
                        ? `Unit: ${unit}`
                        : '';



                /*
                |--------------------------------------------------------------------------
                | CALCULATE
                |--------------------------------------------------------------------------
                */

                calculateRow(row);

            }
        );



        /*
        |--------------------------------------------------------------------------
        | QUANTITY INPUT
        |--------------------------------------------------------------------------
        */

        quantityInput.addEventListener(
            'input',
            function () {

                calculateRow(row);

            }
        );



        /*
        |--------------------------------------------------------------------------
        | REMOVE ROW
        |--------------------------------------------------------------------------
        */

        removeButton.addEventListener(
            'click',
            function () {

                row.remove();

                renumberRows();

                calculateGrandTotal();

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | CALCULATE ROW TOTAL
    |--------------------------------------------------------------------------
    */

    function calculateRow(row) {


        const quantity =
            parseFloat(
                row.querySelector(
                    '.quantity-input'
                ).value
            ) || 0;


        const unitPrice =
            parseFloat(
                row.querySelector(
                    '.unit-price-input'
                ).value
            ) || 0;


        const total =
            quantity * unitPrice;


        row.querySelector(
            '.row-total'
        ).textContent =
            `৳${formatMoney(total)}`;


        calculateGrandTotal();

    }



    /*
    |--------------------------------------------------------------------------
    | CALCULATE GRAND TOTAL
    |--------------------------------------------------------------------------
    */

    function calculateGrandTotal() {


        let total = 0;


        const rows =
            materialRows.querySelectorAll(
                '.material-row'
            );


        rows.forEach(function (row) {


            const rowTotal =
                parseFloat(
                    row.querySelector(
                        '.row-total'
                    ).textContent
                    .replace('৳', '')
                    .replace(/,/g, '')
                ) || 0;


            total += rowTotal;

        });


        grandTotal.textContent =
            `৳${formatMoney(total)}`;

    }



    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        return Number(value)
            .toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    }



    /*
    |--------------------------------------------------------------------------
    | RENUMBER ROWS
    |--------------------------------------------------------------------------
    */

    function renumberRows() {


        const rows =
            materialRows.querySelectorAll(
                '.material-row'
            );


        rows.forEach(
            function (row, index) {

                row.querySelector(
                    '.material-serial'
                ).textContent =
                    index + 1;

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | PROJECT STATUS
    |--------------------------------------------------------------------------
    */

    function updateProjectStatus() {


        const selectedOption =
            projectSelect.options[
                projectSelect.selectedIndex
            ];


        if (!selectedOption.value) {

            projectStatusMessage.textContent = '';

            addMaterialButton.disabled = false;

            saveButton.disabled = false;

            return;

        }


        const status =
            selectedOption.dataset.status;


        /*
        |--------------------------------------------------------------------------
        | CANCELLED PROJECT
        |--------------------------------------------------------------------------
        */

        if (status === 'cancelled') {

            projectStatusMessage.textContent =
                'This project is cancelled. Materials cannot be added.';


            projectStatusMessage.style.color =
                '#dc2626';


            addMaterialButton.disabled =
                true;


            saveButton.disabled =
                true;


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVE PROJECT
        |--------------------------------------------------------------------------
        */

        projectStatusMessage.textContent =
            `Project status: ${status
                .replace('-', ' ')
                .replace(/\b\w/g, function (letter) {
                    return letter.toUpperCase();
                })}`;


        projectStatusMessage.style.color =
            '#6b7280';


        addMaterialButton.disabled =
            false;


        saveButton.disabled =
            false;

    }



    /*
    |--------------------------------------------------------------------------
    | PROJECT CHANGE
    |--------------------------------------------------------------------------
    */

    projectSelect.addEventListener(
        'change',
        updateProjectStatus
    );



    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT PROTECTION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            'projectMaterialForm'
        )
        .addEventListener(
            'submit',
            function (event) {


                /*
                |--------------------------------------------------------------------------
                | PROJECT REQUIRED
                |--------------------------------------------------------------------------
                */

                if (!projectSelect.value) {

                    event.preventDefault();

                    alert(
                        'Please select a project.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CANCELLED PROJECT
                |--------------------------------------------------------------------------
                */

                const selectedOption =
                    projectSelect.options[
                        projectSelect.selectedIndex
                    ];


                if (
                    selectedOption.dataset.status
                    ===
                    'cancelled'
                ) {

                    event.preventDefault();

                    alert(
                        'Materials cannot be added to a cancelled project.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | AT LEAST ONE ROW
                |--------------------------------------------------------------------------
                */

                const rows =
                    materialRows.querySelectorAll(
                        '.material-row'
                    );


                if (rows.length === 0) {

                    event.preventDefault();

                    alert(
                        'Please add at least one material.'
                    );

                    return;

                }

            }
        );



    /*
    |--------------------------------------------------------------------------
    | INITIAL ROW
    |--------------------------------------------------------------------------
    */

    addMaterialRow();



    /*
    |--------------------------------------------------------------------------
    | ADD MATERIAL BUTTON
    |--------------------------------------------------------------------------
    */

    addMaterialButton.addEventListener(
        'click',
        function () {

            addMaterialRow();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | INITIAL PROJECT STATUS
    |--------------------------------------------------------------------------
    */

    updateProjectStatus();


});

</script>


@endsection