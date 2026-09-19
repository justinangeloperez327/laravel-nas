<?php

namespace App\Modules\Organization\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Organization\Models\BusinessUnit;
use App\Modules\Organization\Models\Company;
use App\Modules\Organization\Models\CostCenter;
use App\Modules\Organization\Models\Department;
use App\Modules\Organization\Models\Location;
use App\Modules\Organization\Models\Position;
use App\Modules\Organization\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('organization.view'), 403);

        return Inertia::render('organization/index', [
            'companies' => Company::query()->orderBy('name')->get(),
            'businessUnits' => BusinessUnit::query()->with('company:id,name')->orderBy('name')->get(),
            'departments' => Department::query()->with('businessUnit:id,company_id,name')->orderBy('name')->get(),
            'sections' => Section::query()->with('department:id,business_unit_id,name')->orderBy('name')->get(),
            'positions' => Position::query()->with('company:id,name')->orderBy('name')->get(),
            'locations' => Location::query()->with('company:id,name')->orderBy('name')->get(),
            'costCenters' => CostCenter::query()->with('company:id,name')->orderBy('name')->get(),
        ]);
    }
}
