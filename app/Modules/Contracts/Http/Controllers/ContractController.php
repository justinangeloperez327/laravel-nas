<?php

namespace App\Modules\Contracts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Models\Client;
use App\Modules\Contracts\Actions\CreateContract;
use App\Modules\Contracts\Http\Requests\ContractRequest;
use App\Modules\Contracts\Models\Contract;
use App\Modules\Organization\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContractController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('contracts.view'), 403);

        return Inertia::render('contracts/index', [
            'contracts' => Contract::query()->with(['client:id,name', 'company:id,name'])->withCount('projects')->orderByDesc('id')->get(),
            'clients' => Client::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'companies' => Company::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(ContractRequest $request, CreateContract $createContract): RedirectResponse
    {
        $createContract->execute($request->validated());

        return back()->with('success', 'Contract created successfully.');
    }

    public function update(ContractRequest $request, Contract $contract): RedirectResponse
    {
        $contract->update($request->validated());

        return back()->with('success', 'Contract updated successfully.');
    }
}
