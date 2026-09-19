<?php

namespace App\Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HumanResources\Http\Requests\EmployeeRequest;
use App\Modules\HumanResources\Models\Employee;
use App\Modules\Organization\Models\Company;
use App\Modules\Organization\Models\Department;
use App\Modules\Organization\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HumanResourcesController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('human-resources.view'), 403);

        return Inertia::render('human-resources/index', [
            'employees' => Employee::query()
                ->with(['company:id,name', 'department:id,name', 'position:id,name'])
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get(),
            'companies' => Company::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'positions' => Position::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        Employee::query()->create($request->validated());

        return back()->with('success', 'Employee created successfully.');
    }
}
