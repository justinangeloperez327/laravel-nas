<?php

namespace App\Modules\Projects\Actions;

use App\Modules\Projects\Models\Project;
use Illuminate\Validation\ValidationException;

class UpdateProject
{
    public function execute(Project $project, array $data): Project
    {
        if ($project->status === 'completed' && $data['status'] !== 'completed') {
            throw ValidationException::withMessages([
                'status' => 'A completed project cannot be reopened without a dedicated reopen workflow.',
            ]);
        }

        $project->update($data);

        return $project->refresh();
    }
}
