<?php

namespace App\Modules\DocumentControl\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contracts\Models\Contract;
use App\Modules\DocumentControl\Actions\DecideDocumentRevision;
use App\Modules\DocumentControl\Actions\SubmitDocumentRevision;
use App\Modules\DocumentControl\Http\Requests\DocumentRequest;
use App\Modules\DocumentControl\Http\Requests\DocumentRevisionRequest;
use App\Modules\DocumentControl\Models\Document;
use App\Modules\DocumentControl\Models\DocumentRevision;
use App\Modules\Projects\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('documents.view'), 403);

        return Inertia::render('documents/index', [
            'documents' => Document::query()->with(['project:id,name,project_number', 'revisions' => fn ($query) => $query->latest('id')])->orderByDesc('id')->get(),
            'projects' => Project::query()->orderBy('name')->get(['id', 'name', 'project_number']),
            'contracts' => Contract::query()->orderBy('title')->get(['id', 'title', 'contract_number']),
        ]);
    }

    public function store(DocumentRequest $request): RedirectResponse
    {
        Document::query()->create($request->validated());

        return back()->with('success', 'Document created successfully.');
    }

    public function storeRevision(DocumentRevisionRequest $request): RedirectResponse
    {
        DocumentRevision::query()->create([
            ...$request->validated(),
            'status' => 'draft',
            'created_by_user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Document revision created successfully.');
    }

    public function submit(Request $request, DocumentRevision $documentRevision, SubmitDocumentRevision $submit): RedirectResponse
    {
        abort_unless($request->user()?->can('documents.submit'), 403);
        $submit->execute($documentRevision, $request->user());

        return back()->with('success', 'Document revision submitted for approval.');
    }

    public function decide(Request $request, DocumentRevision $documentRevision, DecideDocumentRevision $decide): RedirectResponse
    {
        abort_unless($request->user()?->can('documents.approve'), 403);
        $request->validate(['action' => ['required', 'in:approved,rejected,returned'], 'comments' => ['nullable', 'string', 'max:2000']]);
        $decide->execute($documentRevision, $request->user(), $request->string('action')->toString(), $request->input('comments'));

        return back()->with('success', 'Document revision decision recorded.');
    }
}
