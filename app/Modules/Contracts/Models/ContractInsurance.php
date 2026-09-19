<?php

namespace App\Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractInsurance extends Model
{
    protected $fillable = ['contract_id', 'type', 'policy_number', 'provider', 'coverage_amount', 'start_date', 'expiry_date'];

    protected function casts(): array
    {
        return ['coverage_amount' => 'decimal:2', 'start_date' => 'date', 'expiry_date' => 'date'];
    }

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
}
