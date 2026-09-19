<?php

namespace App\Modules\Clients\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Http\Requests\ClientAddressRequest;
use App\Modules\Clients\Models\ClientAddress;
use Illuminate\Http\RedirectResponse;

class ClientAddressController extends Controller
{
    public function store(ClientAddressRequest $request): RedirectResponse
    {
        ClientAddress::query()->create($request->validated());

        return back()->with('success', 'Client address added successfully.');
    }
}
