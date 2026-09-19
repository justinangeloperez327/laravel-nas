<?php

namespace App\Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMilestone extends Model
{
    protected $fillable = ['project_id', 'name', 'description', 'due_date', 'completed_at', 'status'];

    protected function casts(): array
    {
        return ['due_date' => 'date', 'completed_at' => 'datetime'];
    }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
}
