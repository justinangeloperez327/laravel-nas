<?php

namespace App\Modules\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierBankAccount extends Model
{
    protected $fillable = ['supplier_id', 'bank_name', 'account_name', 'iban', 'swift_code', 'currency', 'is_primary'];

    protected function casts(): array
    {
        return ['iban' => 'encrypted', 'swift_code' => 'encrypted', 'is_primary' => 'boolean'];
    }

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
}
