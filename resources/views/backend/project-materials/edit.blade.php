@extends('backend.layouts.admin')

@section('title', 'Edit Project Materials')

@section('page_title', 'Edit Project Materials')

@section('content')


<div class="page-header">

    <div>

        <h1>
            Edit Project Materials
        </h1>

        <p>
            Update materials for {{ $project->project_name }}.
        </p>

    </div>


    <a
        href="{{ route(
            'admin.project-materials.show',
            $project->projectMaterials->first()
        ) }}"
        class="secondary-btn"
    >
        ← Back
    </a>

</div>



<div class="panel">

    <div class="panel-header">

        <div>

            <h2>
                {{ $project->project_name }}
            </h2>

            <p>
                Update quantities, suppliers and materials.
            </p>

        </div>

    </div>



    <div class="form-container">

        <form
            action="{{ route(
                'admin.project-materials.update',
                $project->projectMaterials->first()
            ) }}"
            method="POST"
        >

            @csrf


            {{-- =================================================
                PROJECT
            ================================================== --}}

            <div class="form-group">

                <label>
                    Project
                </label>

                <input
                    type="text"
                    value="{{ $project->project_name }}"
                    readonly
                >

                <input
                    type="hidden"
                    name="project_id"
                    value="{{ $project->id }}"
                >

            </div>



            {{-- =================================================
                MATERIALS
            ================================================== --}}

            <div class="material-section">


                <div class="material-section-header">

                    <div>

                        <h3>
                            Materials
                        </h3>

                        <p>
                            Add, edit or remove materials for this project.
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



                <div class="table-wrapper">

                    <table
                        class="material-entry-table"
                    >

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>MATERIAL</th>

                                <th>SUPPLIER</th>

                                <th>QUANTITY</th>

                                <th>UNIT PRICE</th>

                                <th>TOTAL</th>

                                <th>ACTION</th>

                            </tr>

                        </thead>


                        <tbody id="materialRows">

                            @foreach(
                                $project->projectMaterials
                                as $projectMaterial
                            )

                                <tr
                                    class="material-row"
                                >

                                    <td
                                        class="material-serial"
                                    >
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Material --}}

                                    <td>

                                        <select
                                            name="materials[{{ $loop->index }}][material_id]"
                                            class="material-select"
                                            required
                                        >

                                            <option value="">
                                                -- Select Material --
                                            </option>


                                            @foreach($materials as $material)

                                                <option
                                                    value="{{ $material->id }}"
                                                    data-price="{{ $material->unit_price }}"
                                                    data-unit="{{ $material->unit }}"
                                                    {{ $projectMaterial->material_id == $material->id ? 'selected' : '' }}
                                                >

                                                    {{ $material->material_name }}

                                                </option>

                                            @endforeach

                                        </select>


                                        <small class="material-unit">

                                            @if(
                                                $projectMaterial->material &&
                                                $projectMaterial->material->unit
                                            )

                                                Unit:
                                                {{ $projectMaterial->material->unit }}

                                            @endif

                                        </small>

                                    </td>


                                    {{-- Supplier --}}

                                    <td>

                                        <select
                                            name="materials[{{ $loop->index }}][supplier_id]"
                                            class="supplier-select"
                                            required
                                        >

                                            <option value="">
                                                -- Select Supplier --
                                            </option>


                                            @foreach($suppliers as $supplier)

                                                <option
                                                    value="{{ $supplier->id }}"
                                                    {{ $projectMaterial->supplier_id == $supplier->id ? 'selected' : '' }}
                                                >

                                                    {{ $supplier->supplier_name }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    {{-- Quantity --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="materials[{{ $loop->index }}][quantity]"
                                            class="quantity-input"
                                            value="{{ $projectMaterial->quantity }}"
                                            min="0.01"
                                            step="0.01"
                                            required
                                        >

                                    </td>


                                    {{-- Unit Price --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="materials[{{ $loop->index }}][unit_price]"
                                            class="unit-price-input"
                                            value="{{ $projectMaterial->unit_price }}"
                                            min="0"
                                            step="0.01"
                                            readonly
                                        >

                                    </td>


                                    {{-- Total --}}

                                    <td>

                                        <strong class="row-total">

                                            {{ number_format(
                                                (float) $projectMaterial->total_price,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    {{-- Action --}}

                                    <td>

                                        <input
                                            type="hidden"
                                            name="materials[{{ $loop->index }}][id]"
                                            value="{{ $projectMaterial->id }}"
                                        >


                                        <button
                                            type="button"
                                            class="small-action delete remove-material"
                                        >
                                            Remove
                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot>

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:right;"
                                >

                                    <strong>
                                        GRAND TOTAL
                                    </strong>

                                </td>


                                <td>

                                    <strong id="grandTotal">
                                        0.00
                                    </strong>

                                </td>

                                <td></td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>



            {{-- Actions --}}

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
                >
                    Update Project Materials
                </button>

            </div>

        </form>

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    const materials = @json($materials);

    const suppliers = @json($suppliers);

    const materialRows =
        document.getElementById('materialRows');

    const addMaterialButton =
        document.getElementById('addMaterialButton');

    const grandTotal =
        document.getElementById('grandTotal');


    let rowNumber =
        materialRows.querySelectorAll('.material-row').length;


    /*
    |--------------------------------------------------------------------------
    | Calculate Grand Total
    |--------------------------------------------------------------------------
    */

    function calculateGrandTotal() {

        let total = 0;

        const rows =
            materialRows.querySelectorAll('.material-row');


        rows.forEach(function (row) {

            const rowTotal =
                parseFloat(
                    row.querySelector('.row-total').textContent
                ) || 0;

            total += rowTotal;

        });


        grandTotal.textContent =
            total.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Row
    |--------------------------------------------------------------------------
    */

    function calculateRow(row) {

        const quantity =
            parseFloat(
                row.querySelector('.quantity-input').value
            ) || 0;


        const unitPrice =
            parseFloat(
                row.querySelector('.unit-price-input').value
            ) || 0;


        const total =
            quantity * unitPrice;


        row.querySelector('.row-total')
            .textContent = total.toFixed(2);


        calculateGrandTotal();

    }


    /*
    |--------------------------------------------------------------------------
    | Material Change
    |--------------------------------------------------------------------------
    */

    function setupRow(row) {

        const materialSelect =
            row.querySelector('.material-select');

        const quantityInput =
            row.querySelector('.quantity-input');

        const unitPriceInput =
            row.querySelector('.unit-price-input');

        const materialUnit =
            row.querySelector('.material-unit');


        materialSelect.addEventListener(
            'change',
            function () {

                const option =
                    materialSelect.options[
                        materialSelect.selectedIndex
                    ];


                if (!option.value) {

                    unitPriceInput.value = '';

                    materialUnit.textContent = '';

                    calculateRow(row);

                    return;

                }


                const price =
                    parseFloat(
                        option.dataset.price
                    ) || 0;


                const unit =
                    option.dataset.unit || '';


                unitPriceInput.value =
                    price.toFixed(2);


                materialUnit.textContent =
                    unit
                        ? `Unit: ${unit}`
                        : '';


                calculateRow(row);

            }
        );


        quantityInput.addEventListener(
            'input',
            function () {

                calculateRow(row);

            }
        );


        row.querySelector('.remove-material')
            .addEventListener(
                'click',
                function () {

                    row.remove();

                    renumberRows();

                    calculateGrandTotal();

                }
            );


        // Calculate existing row.
        calculateRow(row);

    }


    /*
    |--------------------------------------------------------------------------
    | Add New Row
    |--------------------------------------------------------------------------
    */

    function addMaterialRow() {

        rowNumber++;


        let materialOptions = `
            <option value="">
                -- Select Material --
            </option>
        `;


        materials.forEach(function (material) {

            materialOptions += `
                <option
                    value="${material.id}"
                    data-price="${material.unit_price}"
                    data-unit="${material.unit ?? ''}"
                >
                    ${escapeHtml(material.material_name)}
                </option>
            `;

        });


        let supplierOptions = `
            <option value="">
                -- Select Supplier --
            </option>
        `;


        suppliers.forEach(function (supplier) {

            supplierOptions += `
                <option value="${supplier.id}">
                    ${escapeHtml(supplier.supplier_name)}
                </option>
            `;

        });


        const row =
            document.createElement('tr');


        row.classList.add('material-row');


        row.innerHTML = `

            <td class="material-serial">
                ${rowNumber}
            </td>

            <td>

                <select
                    name="materials[new_${rowNumber}][material_id]"
                    class="material-select"
                    required
                >

                    ${materialOptions}

                </select>

                <small class="material-unit"></small>

            </td>

            <td>

                <select
                    name="materials[new_${rowNumber}][supplier_id]"
                    class="supplier-select"
                    required
                >

                    ${supplierOptions}

                </select>

            </td>

            <td>

                <input
                    type="number"
                    name="materials[new_${rowNumber}][quantity]"
                    class="quantity-input"
                    min="0.01"
                    step="0.01"
                    required
                >

            </td>

            <td>

                <input
                    type="number"
                    name="materials[new_${rowNumber}][unit_price]"
                    class="unit-price-input"
                    min="0"
                    step="0.01"
                    readonly
                >

            </td>

            <td>

                <strong class="row-total">
                    0.00
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


        materialRows.appendChild(row);


        setupRow(row);

    }


    /*
    |--------------------------------------------------------------------------
    | Renumber
    |--------------------------------------------------------------------------
    */

    function renumberRows() {

        const rows =
            materialRows.querySelectorAll('.material-row');


        rows.forEach(function (row, index) {

            row.querySelector(
                '.material-serial'
            ).textContent = index + 1;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
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
    | Setup Existing Rows
    |--------------------------------------------------------------------------
    */

    materialRows
        .querySelectorAll('.material-row')
        .forEach(function (row) {

            setupRow(row);

        });


    /*
    |--------------------------------------------------------------------------
    | Add Material
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
    | Initial Grand Total
    |--------------------------------------------------------------------------
    */

    calculateGrandTotal();

});

</script>


@endsection