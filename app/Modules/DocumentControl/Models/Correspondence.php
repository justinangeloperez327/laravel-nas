<?php

namespace App\Modules\DocumentControl\Models;

use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Correspondence extends Model
{
    protected $fillable = ['project_id', 'reference_number', 'direction', 'party_name', 'subject', 'summary', 'correspondence_date'];

    protected function casts(): array { return ['correspondence_date' => 'date']; }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
}
