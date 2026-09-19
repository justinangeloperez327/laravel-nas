<?php

namespace App\Modules\DocumentControl\Models;

use App\Modules\Contracts\Models\Contract;
use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = ['project_id', 'contract_id', 'document_number', 'title', 'document_type', 'discipline', 'status'];

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function revisions(): HasMany { return $this->hasMany(DocumentRevision::class); }
}
