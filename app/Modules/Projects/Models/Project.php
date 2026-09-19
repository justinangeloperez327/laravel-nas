<?php

namespace App\Modules\Projects\Models;

use App\Modules\Clients\Models\Client;
use App\Modules\Contracts\Models\Contract;
use App\Modules\Organization\Models\BusinessUnit;
use App\Modules\Organization\Models\Company;
use App\Modules\Organization\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'client_id', 'contract_id', 'company_id', 'business_unit_id', 'location_id',
        'project_number', 'name', 'description', 'status',
        'start_date', 'planned_completion_date', 'actual_completion_date',
        'contract_value', 'progress_percentage',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'planned_completion_date' => 'date',
            'actual_completion_date' => 'date',
            'contract_value' => 'decimal:2',
            'progress_percentage' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function businessUnit(): BelongsTo { return $this->belongsTo(BusinessUnit::class); }
    public function location(): BelongsTo { return $this->belongsTo(Location::class); }
    public function milestones(): HasMany { return $this->hasMany(ProjectMilestone::class); }
    public function risks(): HasMany { return $this->hasMany(ProjectRisk::class); }
    public function issues(): HasMany { return $this->hasMany(ProjectIssue::class); }
}
