<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\ClientController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\MaterialController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\ProjectMaterialController;
use App\Http\Controllers\Backend\BudgetController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ProgressReportController;
use App\Http\Controllers\Backend\ReportController;

// =========================================================
// AUTHENTICATION ROUTES
// =========================================================

// Show login form
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// Process login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');


// =========================================================
// ADMIN PANEL ROUTES
// =========================================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {


        // =====================================================
        // DASHBOARD
        // =====================================================

        // GET /admin/dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        // =====================================================
        // CLIENT MANAGEMENT
        // =====================================================

        // Show all clients
        // GET /admin/clients
        Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.index');


        // Show create client form
        // GET /admin/clients/create
        Route::get('/clients/create', [ClientController::class, 'create'])
            ->name('clients.create');


        // Save new client
        // POST /admin/clients
        Route::post('/clients', [ClientController::class, 'store'])
            ->name('clients.store');


        // Show one client
        // GET /admin/clients/{client}
        Route::get('/clients/{client}', [ClientController::class, 'show'])
            ->name('clients.show');


        // Show edit client form
        // GET /admin/clients/{client}/edit
        Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])
            ->name('clients.edit');


        // Update client
        // POST /admin/clients/{client}/update
        Route::post('/clients/{client}/update', [ClientController::class, 'update'])
            ->name('clients.update');


        // Delete client
        // POST /admin/clients/{client}/delete
        Route::post('/clients/{client}/delete', [ClientController::class, 'destroy'])
            ->name('clients.destroy');



        // =====================================================
        // PROJECT MANAGEMENT
        // =====================================================

        // Show all projects
        // GET /admin/projects
        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('projects.index');


        // Show create project form
        // GET /admin/projects/create
        Route::get('/projects/create', [ProjectController::class, 'create'])
            ->name('projects.create');


        // Save new project
        // POST /admin/projects
        Route::post('/projects', [ProjectController::class, 'store'])
            ->name('projects.store');


        // Show one project
        // GET /admin/projects/{project}
        Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->name('projects.show');


        // Show edit project form
        // GET /admin/projects/{project}/edit
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
            ->name('projects.edit');


        // Update project
        // POST /admin/projects/{project}/update
        Route::post('/projects/{project}/update', [ProjectController::class, 'update'])
            ->name('projects.update');


        // Delete project
        // POST /admin/projects/{project}/delete
        Route::post('/projects/{project}/delete', [ProjectController::class, 'destroy'])
            ->name('projects.destroy');



        // =====================================================
        // MATERIAL MANAGEMENT
        // =====================================================

        // Show all materials
        // GET /admin/materials
        Route::get('/materials', [MaterialController::class, 'index'])
            ->name('materials.index');


        // Show create material form
        // GET /admin/materials/create
        Route::get('/materials/create', [MaterialController::class, 'create'])
            ->name('materials.create');


        // Save new material
        // POST /admin/materials
        Route::post('/materials', [MaterialController::class, 'store'])
            ->name('materials.store');


        // Show one material
        // GET /admin/materials/{material}
        Route::get('/materials/{material}', [MaterialController::class, 'show'])
            ->name('materials.show');


        // Show edit material form
        // GET /admin/materials/{material}/edit
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])
            ->name('materials.edit');


        // Update material
        // POST /admin/materials/{material}/update
        Route::post('/materials/{material}/update', [MaterialController::class, 'update'])
            ->name('materials.update');


        // Delete material
        // POST /admin/materials/{material}/delete
        Route::post('/materials/{material}/delete', [MaterialController::class, 'destroy'])
            ->name('materials.destroy');
        // =========================================================
        // SUPPLIER MANAGEMENT
        // =========================================================


        // Show all suppliers
        // GET /admin/suppliers

        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');


        // Show create supplier form
        // GET /admin/suppliers/create

        Route::get('/suppliers/create', [SupplierController::class, 'create'])
            ->name('suppliers.create');


        // Save new supplier
        // POST /admin/suppliers

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');


        // Show one supplier
        // GET /admin/suppliers/{supplier}

        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])
            ->name('suppliers.show');


        // Show edit supplier form
        // GET /admin/suppliers/{supplier}/edit

        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
            ->name('suppliers.edit');


        // Update supplier
        // POST /admin/suppliers/{supplier}/update

        Route::post('/suppliers/{supplier}/update', [SupplierController::class, 'update'])
            ->name('suppliers.update');


        // Delete supplier
        // POST /admin/suppliers/{supplier}/delete

        Route::post('/suppliers/{supplier}/delete', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');

        // =========================================================
        // PROJECT MATERIAL MANAGEMENT
        // =========================================================


        // Show all project materials
        // GET /admin/project-materials

        Route::get(
            '/project-materials',
            [ProjectMaterialController::class, 'index']
        )->name('project-materials.index');


        // Show create project material form
        // GET /admin/project-materials/create

        Route::get(
            '/project-materials/create',
            [ProjectMaterialController::class, 'create']
        )->name('project-materials.create');


        // Save new project material
        // POST /admin/project-materials

        Route::post(
            '/project-materials',
            [ProjectMaterialController::class, 'store']
        )->name('project-materials.store');


        // Show one project material
        // GET /admin/project-materials/{projectMaterial}

        Route::get(
            '/project-materials/{projectMaterial}',
            [ProjectMaterialController::class, 'show']
        )->name('project-materials.show');


        // Show edit project material form
        // GET /admin/project-materials/{projectMaterial}/edit

        Route::get(
            '/project-materials/{projectMaterial}/edit',
            [ProjectMaterialController::class, 'edit']
        )->name('project-materials.edit');


        // Update project material
        // POST /admin/project-materials/{projectMaterial}/update

        Route::post(
            '/project-materials/{projectMaterial}/update',
            [ProjectMaterialController::class, 'update']
        )->name('project-materials.update');


        // Delete project material
        // POST /admin/project-materials/{projectMaterial}/delete

        Route::post(
            '/project-materials/{projectMaterial}/delete',
            [ProjectMaterialController::class, 'destroy']
        )->name('project-materials.destroy');

        // =====================================================
        // BUDGET MANAGEMENT
        // =====================================================

        // Show all budgets
        // GET /admin/budgets

        Route::get('/budgets', [BudgetController::class, 'index'])
            ->name('budgets.index');


        // Show create budget form
        // GET /admin/budgets/create

        Route::get('/budgets/create', [BudgetController::class, 'create'])
            ->name('budgets.create');


        // Save new budget
        // POST /admin/budgets

        Route::post('/budgets', [BudgetController::class, 'store'])
            ->name('budgets.store');


        // Show one budget
        // GET /admin/budgets/{budget}

        Route::get('/budgets/{budget}', [BudgetController::class, 'show'])
            ->name('budgets.show');


        // Show edit budget form
        // GET /admin/budgets/{budget}/edit

        Route::get('/budgets/{budget}/edit', [BudgetController::class, 'edit'])
            ->name('budgets.edit');


        // Update budget
        // POST /admin/budgets/{budget}/update

        Route::post('/budgets/{budget}/update', [BudgetController::class, 'update'])
            ->name('budgets.update');


        // Delete budget
        // POST /admin/budgets/{budget}/delete

        Route::post('/budgets/{budget}/delete', [BudgetController::class, 'destroy'])
            ->name('budgets.destroy');
        // =========================================================
        // PAYMENT MANAGEMENT
        // =========================================================


        // Show all payment summaries
        // GET /admin/payments

        Route::get('/payments', [PaymentController::class, 'index'])
            ->name('payments.index');


        // Show create payment form
        // GET /admin/payments/create

        Route::get('/payments/create', [PaymentController::class, 'create'])
            ->name('payments.create');


        // Save new payment
        // POST /admin/payments

        Route::post('/payments', [PaymentController::class, 'store'])
            ->name('payments.store');


        // Show one payment
        // GET /admin/payments/{payment}

        Route::get('/payments/{payment}', [PaymentController::class, 'show'])
            ->name('payments.show');


        // Show edit payment form
        // GET /admin/payments/{payment}/edit

        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])
            ->name('payments.edit');


        // Update payment
        // POST /admin/payments/{payment}/update

        Route::post('/payments/{payment}/update', [PaymentController::class, 'update'])
            ->name('payments.update');


        // Delete payment
        // POST /admin/payments/{payment}/delete

        Route::post('/payments/{payment}/delete', [PaymentController::class, 'destroy'])
            ->name('payments.destroy');

        // =====================================================
        // PROGRESS REPORTS
        // =====================================================

        Route::get(
            '/progress-reports',
            [ProgressReportController::class, 'index']
        )->name('progress-reports.index');


        Route::get(
            '/progress-reports/create',
            [ProgressReportController::class, 'create']
        )->name('progress-reports.create');


        Route::post(
            '/progress-reports',
            [ProgressReportController::class, 'store']
        )->name('progress-reports.store');


        Route::get(
            '/progress-reports/{progressReport}',
            [ProgressReportController::class, 'show']
        )->name('progress-reports.show');


        Route::get(
            '/progress-reports/{progressReport}/edit',
            [ProgressReportController::class, 'edit']
        )->name('progress-reports.edit');


        Route::put(
            '/progress-reports/{progressReport}',
            [ProgressReportController::class, 'update']
        )->name('progress-reports.update');


        Route::delete(
            '/progress-reports/{progressReport}',
            [ProgressReportController::class, 'destroy']
        )->name('progress-reports.destroy');

         /*
        |--------------------------------------------------------------------------
        | REPORT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [ReportController::class, 'index']
        )->name('reports.index');


        /*
        |--------------------------------------------------------------------------
        | REPORT PRINT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/print',
            [ReportController::class, 'print']
        )->name('reports.print');


        /*
        |--------------------------------------------------------------------------
        | SINGLE PROJECT REPORT PRINT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/{project}/print',
            [ReportController::class, 'printProject']
        )->name('reports.print-project');


        /*
        |--------------------------------------------------------------------------
        | SINGLE PROJECT REPORT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/{project}',
            [ReportController::class, 'show']
        )->name('reports.show');
    });
