<?php

namespace App\Modules\DocumentControl\Models;

use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transmittal extends Model
{
    protected $fillable = ['project_id', 'transmittal_number', 'direction', 'party_name', 'subject', 'transmittal_date', 'status'];

    protected function casts(): array { return ['transmittal_date' => 'date']; }

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function revisions(): BelongsToMany { return $this->belongsToMany(DocumentRevision::class, 'transmittal_document_revision'); }
}
