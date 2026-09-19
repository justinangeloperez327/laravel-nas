<?php

namespace App\Modules\Suppliers\Models;

use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPerformanceEvaluation extends Model
{
    protected $fillable = ['supplier_id', 'project_id', 'price_score', 'quality_score', 'delivery_score', 'responsiveness_score', 'comments', 'evaluated_on'];

    protected function casts(): array
    {
        return [
            'price_score' => 'decimal:2',
            'quality_score' => 'decimal:2',
            'delivery_score' => 'decimal:2',
            'responsiveness_score' => 'decimal:2',
            'evaluated_on' => 'date',
        ];
    }

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
}
