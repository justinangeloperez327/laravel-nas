<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\DepartmentRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\Department;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function store(DepartmentRequest $request): RedirectResponse
    {
        Department::query()->create($request->validated());

        return back()->with('success', 'Department created successfully.');
    }

    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return back()->with('success', 'Department updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, Department $department): RedirectResponse
    {
        $department->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Department status updated successfully.');
    }
}
