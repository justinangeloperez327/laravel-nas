<?php

namespace App\Modules\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupplierCategory extends Model
{
    protected $fillable = ['name', 'code', 'is_active'];

    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function suppliers(): BelongsToMany { return $this->belongsToMany(Supplier::class); }
}
