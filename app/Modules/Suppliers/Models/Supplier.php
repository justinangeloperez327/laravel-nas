<?php

namespace App\Modules\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'code', 'legal_name', 'trade_license_number', 'trade_license_expiry',
        'tax_registration_number', 'email', 'phone', 'website', 'status',
    ];

    protected function casts(): array
    {
        return ['trade_license_expiry' => 'date'];
    }

    public function categories(): BelongsToMany { return $this->belongsToMany(SupplierCategory::class); }
    public function contacts(): HasMany { return $this->hasMany(SupplierContact::class); }
    public function documents(): HasMany { return $this->hasMany(SupplierDocument::class); }
    public function bankAccounts(): HasMany { return $this->hasMany(SupplierBankAccount::class); }
    public function performanceEvaluations(): HasMany { return $this->hasMany(SupplierPerformanceEvaluation::class); }
}
