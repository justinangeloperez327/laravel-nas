<?php

namespace Tests\Feature\Inventory;

use App\Modules\Inventory\Actions\RecordStockMovement;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Organization\Models\Company;
use App\Modules\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_cannot_become_negative(): void
    {
        $company = Company::query()->create(['name' => 'NAS', 'code' => 'NAS', 'is_active' => true]);
        $warehouse = Warehouse::query()->create(['company_id' => $company->id, 'code' => 'MAIN', 'name' => 'Main', 'is_active' => true]);
        $item = InventoryItem::query()->create(['sku' => 'ITEM-1', 'name' => 'Item', 'unit' => 'ea', 'reorder_level' => 0, 'is_active' => true]);
        $user = User::factory()->create();

        $this->expectException(ValidationException::class);

        app(RecordStockMovement::class)->execute([
            'warehouse_id' => $warehouse->id,
            'inventory_item_id' => $item->id,
            'project_id' => null,
            'movement_type' => 'issue',
            'quantity' => 1,
            'reference_type' => null,
            'reference_id' => null,
            'notes' => null,
            'occurred_at' => now(),
        ], $user);
    }
}
