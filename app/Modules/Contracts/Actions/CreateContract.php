<?php

namespace App\Modules\Contracts\Actions;

use App\Modules\Contracts\Models\Contract;

class CreateContract
{
    public function execute(array $data): Contract
    {
        return Contract::query()->create($data);
    }
}
