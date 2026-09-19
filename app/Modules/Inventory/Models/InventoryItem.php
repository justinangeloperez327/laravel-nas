<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = ['sku', 'name', 'category', 'unit', 'description', 'reorder_level', 'is_active'];

    protected function casts(): array
    {
        return ['reorder_level' => 'decimal:4', 'is_active' => 'boolean'];
    }

    public function balances(): HasMany
    {
        return $this->hasMany(StockBalance::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
