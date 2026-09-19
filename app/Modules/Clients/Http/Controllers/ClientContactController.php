<?php

namespace App\Modules\Clients\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clients\Http\Requests\ClientContactRequest;
use App\Modules\Clients\Models\ClientContact;
use Illuminate\Http\RedirectResponse;

class ClientContactController extends Controller
{
    public function store(ClientContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['is_primary']) {
            ClientContact::query()->where('client_id', $data['client_id'])->update(['is_primary' => false]);
        }

        ClientContact::query()->create($data);

        return back()->with('success', 'Client contact added successfully.');
    }
}
