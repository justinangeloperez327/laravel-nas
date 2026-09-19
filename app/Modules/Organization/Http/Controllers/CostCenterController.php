<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\CostCenterRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\CostCenter;
use Illuminate\Http\RedirectResponse;

class CostCenterController extends Controller
{
    public function store(CostCenterRequest $request): RedirectResponse
    {
        CostCenter::query()->create($request->validated());

        return back()->with('success', 'Cost center created successfully.');
    }

    public function update(CostCenterRequest $request, CostCenter $costCenter): RedirectResponse
    {
        $costCenter->update($request->validated());

        return back()->with('success', 'Cost center updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, CostCenter $costCenter): RedirectResponse
    {
        $costCenter->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Cost center status updated successfully.');
    }
}
