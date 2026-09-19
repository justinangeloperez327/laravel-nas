<?php

namespace App\Modules\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDocument extends Model
{
    protected $fillable = ['supplier_id', 'document_type', 'document_number', 'issue_date', 'expiry_date', 'storage_provider', 'external_file_id', 'storage_path'];

    protected function casts(): array { return ['issue_date' => 'date', 'expiry_date' => 'date']; }

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
}
