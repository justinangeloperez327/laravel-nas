<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Projects\Models\Project;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'warehouse_id', 'inventory_item_id', 'project_id', 'performed_by_user_id',
        'movement_type', 'quantity', 'reference_type', 'reference_id', 'notes', 'occurred_at',
    ];

    protected function casts(): array
    {
        return ['quantity' => 'decimal:4', 'occurred_at' => 'datetime'];
    }

    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function item(): BelongsTo { return $this->belongsTo(InventoryItem::class, 'inventory_item_id'); }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by_user_id'); }
}
