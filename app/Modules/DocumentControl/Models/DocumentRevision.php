<?php

namespace App\Modules\DocumentControl\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRevision extends Model
{
    protected $fillable = [
        'document_id', 'revision_code', 'status', 'storage_provider', 'external_file_id',
        'storage_path', 'original_filename', 'mime_type', 'file_size', 'checksum',
        'created_by_user_id', 'decided_by_user_id', 'submitted_at', 'decided_at', 'decision_comments',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime', 'decided_at' => 'datetime'];
    }

    public function document(): BelongsTo { return $this->belongsTo(Document::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by_user_id'); }
    public function decider(): BelongsTo { return $this->belongsTo(User::class, 'decided_by_user_id'); }
}
