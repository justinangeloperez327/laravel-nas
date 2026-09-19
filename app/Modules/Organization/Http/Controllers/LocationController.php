<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\LocationRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\Location;
use Illuminate\Http\RedirectResponse;

class LocationController extends Controller
{
    public function store(LocationRequest $request): RedirectResponse
    {
        Location::query()->create($request->validated());

        return back()->with('success', 'Location created successfully.');
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->validated());

        return back()->with('success', 'Location updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, Location $location): RedirectResponse
    {
        $location->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Location status updated successfully.');
    }
}
