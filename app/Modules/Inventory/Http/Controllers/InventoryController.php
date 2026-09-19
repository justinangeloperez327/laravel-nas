<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Actions\RecordStockMovement;
use App\Modules\Inventory\Http\Requests\InventoryItemRequest;
use App\Modules\Inventory\Http\Requests\StockMovementRequest;
use App\Modules\Inventory\Http\Requests\WarehouseRequest;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Models\StockBalance;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Organization\Models\Company;
use App\Modules\Projects\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('inventory.view'), 403);

        return Inertia::render('inventory/index', [
            'items' => InventoryItem::query()->where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->with(['company:id,name', 'project:id,name'])->where('is_active', true)->orderBy('name')->get(),
            'balances' => StockBalance::query()->with(['warehouse:id,name', 'item:id,sku,name,unit'])->orderBy('warehouse_id')->get(),
            'companies' => Company::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'projects' => Project::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeItem(InventoryItemRequest $request): RedirectResponse
    {
        InventoryItem::query()->create($request->validated());

        return back()->with('success', 'Inventory item created successfully.');
    }

    public function storeWarehouse(WarehouseRequest $request): RedirectResponse
    {
        Warehouse::query()->create($request->validated());

        return back()->with('success', 'Warehouse created successfully.');
    }

    public function move(StockMovementRequest $request, RecordStockMovement $record): RedirectResponse
    {
        $record->execute($request->validated(), $request->user());

        return back()->with('success', 'Stock movement recorded successfully.');
    }
}
