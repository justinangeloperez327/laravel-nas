<?php

namespace App\Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAmendment extends Model
{
    protected $fillable = ['contract_id', 'reference', 'title', 'description', 'value_change', 'time_change_days', 'effective_date', 'status'];

    protected function casts(): array
    {
        return ['value_change' => 'decimal:2', 'effective_date' => 'date'];
    }

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
}
