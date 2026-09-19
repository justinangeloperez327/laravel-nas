<?php

namespace App\Modules\Projects\Actions;

use App\Modules\Projects\Models\Project;

class CreateProject
{
    public function execute(array $data): Project
    {
        return Project::query()->create($data);
    }
}
