<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Http\Requests\PositionRequest;
use App\Modules\Organization\Models\Position;
use Illuminate\Http\RedirectResponse;

class PositionController extends Controller
{
    public function store(PositionRequest $request): RedirectResponse
    {
        Position::query()->create($request->validated());

        return back()->with('success', 'Position created successfully.');
    }

    public function update(PositionRequest $request, Position $position): RedirectResponse
    {
        $position->update($request->validated());

        return back()->with('success', 'Position updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, Position $position): RedirectResponse
    {
        $position->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Position status updated successfully.');
    }
}
