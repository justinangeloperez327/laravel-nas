<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Organization\Models\Company;
use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = ['company_id', 'project_id', 'code', 'name', 'location', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(StockBalance::class);
    }
}
