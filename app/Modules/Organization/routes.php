<?php

use App\Modules\Organization\Http\Controllers\BusinessUnitController;
use App\Modules\Organization\Http\Controllers\CompanyController;
use App\Modules\Organization\Http\Controllers\CostCenterController;
use App\Modules\Organization\Http\Controllers\DepartmentController;
use App\Modules\Organization\Http\Controllers\LocationController;
use App\Modules\Organization\Http\Controllers\OrganizationController;
use App\Modules\Organization\Http\Controllers\PositionController;
use App\Modules\Organization\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])
    ->prefix('administration/organization')
    ->group(function (): void {
        Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');

        Route::post('companies', [CompanyController::class, 'store']);
        Route::put('companies/{company}', [CompanyController::class, 'update']);
        Route::patch('companies/{company}/status', [CompanyController::class, 'status']);

        Route::post('business-units', [BusinessUnitController::class, 'store']);
        Route::put('business-units/{businessUnit}', [BusinessUnitController::class, 'update']);
        Route::patch('business-units/{businessUnit}/status', [BusinessUnitController::class, 'status']);

        Route::post('departments', [DepartmentController::class, 'store']);
        Route::put('departments/{department}', [DepartmentController::class, 'update']);
        Route::patch('departments/{department}/status', [DepartmentController::class, 'status']);

        Route::post('sections', [SectionController::class, 'store']);
        Route::put('sections/{section}', [SectionController::class, 'update']);
        Route::patch('sections/{section}/status', [SectionController::class, 'status']);

        Route::post('positions', [PositionController::class, 'store']);
        Route::put('positions/{position}', [PositionController::class, 'update']);
        Route::patch('positions/{position}/status', [PositionController::class, 'status']);

        Route::post('locations', [LocationController::class, 'store']);
        Route::put('locations/{location}', [LocationController::class, 'update']);
        Route::patch('locations/{location}/status', [LocationController::class, 'status']);

        Route::post('cost-centers', [CostCenterController::class, 'store']);
        Route::put('cost-centers/{costCenter}', [CostCenterController::class, 'update']);
        Route::patch('cost-centers/{costCenter}/status', [CostCenterController::class, 'status']);
    });
