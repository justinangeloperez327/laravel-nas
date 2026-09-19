<?php

namespace App\Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRisk extends Model
{
    protected $fillable = ['project_id', 'title', 'description', 'probability', 'impact', 'status', 'due_date'];

    protected function casts(): array { return ['due_date' => 'date']; }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
}
