<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\SectionRequest;
use App\Modules\Organization\Http\Requests\OrganizationStatusRequest;
use App\Modules\Organization\Models\Section;
use Illuminate\Http\RedirectResponse;

class SectionController extends Controller
{
    public function store(SectionRequest $request): RedirectResponse
    {
        Section::query()->create($request->validated());

        return back()->with('success', 'Section created successfully.');
    }

    public function update(SectionRequest $request, Section $section): RedirectResponse
    {
        $section->update($request->validated());

        return back()->with('success', 'Section updated successfully.');
    }

    public function status(OrganizationStatusRequest $request, Section $section): RedirectResponse
    {
        $section->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Section status updated successfully.');
    }
}
