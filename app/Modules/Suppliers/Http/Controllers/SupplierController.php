<?php

namespace App\Modules\Suppliers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Suppliers\Http\Requests\SupplierRequest;
use App\Modules\Suppliers\Models\Supplier;
use App\Modules\Suppliers\Models\SupplierCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->can('suppliers.view'), 403);

        return Inertia::render('suppliers/index', [
            'suppliers' => Supplier::query()->with('categories:id,name')->orderBy('name')->get(),
            'categories' => SupplierCategory::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $supplier = Supplier::query()->create(Arr::except($data, 'category_ids'));
        $supplier->categories()->sync($data['category_ids'] ?? []);

        return back()->with('success', 'Supplier created successfully.');
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validated();
        $supplier->update(Arr::except($data, 'category_ids'));
        $supplier->categories()->sync($data['category_ids'] ?? []);

        return back()->with('success', 'Supplier updated successfully.');
    }

    public function approve(Request $request, Supplier $supplier): RedirectResponse
    {
        abort_unless($request->user()?->can('suppliers.approve'), 403);
        $supplier->update(['status' => 'approved']);

        return back()->with('success', 'Supplier approved successfully.');
    }
}
