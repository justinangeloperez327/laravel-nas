<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Models\Client;
use App\Modules\Organization\Models\BusinessUnit;
use App\Modules\Organization\Models\Company;
use App\Modules\Organization\Models\Location;
use App\Modules\Projects\Actions\CreateProject;
use App\Modules\Projects\Actions\UpdateProject;
use App\Modules\Projects\Http\Requests\ProjectRequest;
use App\Modules\Projects\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('projects.view'), 403);

        return Inertia::render('projects/index', [
            'projects' => Project::query()
                ->with(['client:id,name', 'company:id,name', 'businessUnit:id,name', 'location:id,name'])
                ->orderByDesc('id')
                ->get(),
            'clients' => Client::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'companies' => Company::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'businessUnits' => BusinessUnit::query()->where('is_active', true)->orderBy('name')->get(['id', 'company_id', 'name']),
            'locations' => Location::query()->where('is_active', true)->orderBy('name')->get(['id', 'company_id', 'name']),
        ]);
    }

    public function store(ProjectRequest $request, CreateProject $createProject): RedirectResponse
    {
        $createProject->execute($request->validated());

        return back()->with('success', 'Project created successfully.');
    }

    public function update(ProjectRequest $request, Project $project, UpdateProject $updateProject): RedirectResponse
    {
        $updateProject->execute($project, $request->validated());

        return back()->with('success', 'Project updated successfully.');
    }
}
