<?php

namespace App\Modules\Clients\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Http\Requests\ClientRequest;
use App\Modules\Clients\Http\Requests\ClientStatusRequest;
use App\Modules\Clients\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('clients.view'), 403);

        return Inertia::render('clients/index', [
            'clients' => Client::query()->with(['contacts', 'addresses'])->orderBy('name')->get(),
        ]);
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        Client::query()->create($request->validated());

        return back()->with('success', 'Client created successfully.');
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return back()->with('success', 'Client updated successfully.');
    }

    public function status(ClientStatusRequest $request, Client $client): RedirectResponse
    {
        $client->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Client status updated successfully.');
    }
}
