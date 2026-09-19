<?php

namespace App\Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractGuarantee extends Model
{
    protected $fillable = ['contract_id', 'type', 'reference', 'amount', 'issue_date', 'expiry_date', 'status'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'issue_date' => 'date', 'expiry_date' => 'date'];
    }

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
}
