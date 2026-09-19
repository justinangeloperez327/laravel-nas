<?php

namespace App\Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Procurement\Actions\CreatePurchaseRequest;
use App\Modules\Procurement\Actions\DecidePurchaseRequest;
use App\Modules\Procurement\Actions\SubmitPurchaseRequest;
use App\Modules\Procurement\Http\Requests\PurchaseRequestRequest;
use App\Modules\Procurement\Models\PurchaseRequest;
use App\Modules\Projects\Models\Project;
use App\Modules\Suppliers\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProcurementController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('procurement.view'), 403);

        return Inertia::render('procurement/index', [
            'purchaseRequests' => PurchaseRequest::query()
                ->with(['project:id,name,project_number', 'requester:id,name', 'items'])
                ->orderByDesc('id')
                ->get(),
            'projects' => Project::query()->whereIn('status', ['planned', 'active'])->orderBy('name')->get(['id', 'name', 'project_number']),
            'suppliers' => Supplier::query()->where('status', 'approved')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storePurchaseRequest(PurchaseRequestRequest $request, CreatePurchaseRequest $create): RedirectResponse
    {
        $create->execute($request->validated(), $request->user());

        return back()->with('success', 'Purchase Request created successfully.');
    }

    public function submitPurchaseRequest(Request $request, PurchaseRequest $purchaseRequest, SubmitPurchaseRequest $submit): RedirectResponse
    {
        abort_unless($request->user()?->can('purchase-requests.create'), 403);
        $submit->execute($purchaseRequest, $request->user());

        return back()->with('success', 'Purchase Request submitted for approval.');
    }

    public function decidePurchaseRequest(Request $request, PurchaseRequest $purchaseRequest, DecidePurchaseRequest $decide): RedirectResponse
    {
        abort_unless($request->user()?->can('purchase-requests.approve'), 403);
        $request->validate(['action' => ['required', 'in:approved,rejected,returned'], 'comments' => ['nullable', 'string', 'max:2000']]);
        $decide->execute($purchaseRequest, $request->user(), $request->string('action')->toString(), $request->input('comments'));

        return back()->with('success', 'Purchase Request decision recorded.');
    }
}
