<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\CompanyRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\Company;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    public function store(CompanyRequest $request): RedirectResponse
    {
        Company::query()->create($request->validated());

        return back()->with('success', 'Company created successfully.');
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $company->update($request->validated());

        return back()->with('success', 'Company updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, Company $company): RedirectResponse
    {
        $company->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Company status updated successfully.');
    }
}
