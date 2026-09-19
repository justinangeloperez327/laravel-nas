<?php

namespace App\Modules\Approvals\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Approvals\Actions\RecordApprovalAction;
use App\Modules\Approvals\Http\Requests\ApprovalActionRequest;
use App\Modules\Approvals\Http\Requests\ApprovalStepRequest;
use App\Modules\Approvals\Http\Requests\ApprovalWorkflowRequest;
use App\Modules\Approvals\Models\ApprovalRequest;
use App\Modules\Approvals\Models\ApprovalWorkflow;
use App\Modules\Approvals\Models\ApprovalWorkflowStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApprovalController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('approvals.view'), 403);

        return Inertia::render('approvals/index', [
            'workflows' => ApprovalWorkflow::query()->with('steps')->orderBy('name')->get(),
            'requests' => ApprovalRequest::query()->with(['workflow:id,name,code', 'submitter:id,name'])->latest()->limit(100)->get(),
        ]);
    }

    public function storeWorkflow(ApprovalWorkflowRequest $request): RedirectResponse
    {
        ApprovalWorkflow::query()->create($request->validated());

        return back()->with('success', 'Approval workflow created successfully.');
    }

    public function storeStep(ApprovalStepRequest $request): RedirectResponse
    {
        ApprovalWorkflowStep::query()->create($request->validated());

        return back()->with('success', 'Approval step created successfully.');
    }

    public function act(
        ApprovalActionRequest $request,
        ApprovalRequest $approvalRequest,
        RecordApprovalAction $recordApprovalAction,
    ): RedirectResponse {
        $recordApprovalAction->execute(
            $approvalRequest,
            $request->user(),
            $request->validated('action'),
            $request->validated('comments'),
        );

        return back()->with('success', 'Approval action recorded successfully.');
    }
}
