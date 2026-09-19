<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\BusinessUnitRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\BusinessUnit;
use Illuminate\Http\RedirectResponse;

class BusinessUnitController extends Controller
{
    public function store(BusinessUnitRequest $request): RedirectResponse
    {
        BusinessUnit::query()->create($request->validated());

        return back()->with('success', 'Business unit created successfully.');
    }

    public function update(BusinessUnitRequest $request, BusinessUnit $businessUnit): RedirectResponse
    {
        $businessUnit->update($request->validated());

        return back()->with('success', 'Business unit updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, BusinessUnit $businessUnit): RedirectResponse
    {
        $businessUnit->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Business unit status updated successfully.');
    }
}
