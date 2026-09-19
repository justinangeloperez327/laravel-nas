<?php

namespace App\Modules\Contracts\Models;

use App\Modules\Clients\Models\Client;
use App\Modules\Organization\Models\Company;
use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    protected $fillable = [
        'client_id', 'company_id', 'contract_number', 'title', 'scope',
        'currency', 'contract_value', 'retention_percentage', 'advance_percentage',
        'start_date', 'completion_date', 'status', 'payment_terms',
    ];

    protected function casts(): array
    {
        return [
            'contract_value' => 'decimal:2',
            'retention_percentage' => 'decimal:2',
            'advance_percentage' => 'decimal:2',
            'start_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function projects(): HasMany { return $this->hasMany(Project::class); }
    public function amendments(): HasMany { return $this->hasMany(ContractAmendment::class); }
    public function guarantees(): HasMany { return $this->hasMany(ContractGuarantee::class); }
    public function insurances(): HasMany { return $this->hasMany(ContractInsurance::class); }
}
