<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('name');
            $table->string('category', 100)->nullable();
            $table->string('unit', 50);
            $table->text('description')->nullable();
            $table->decimal('reorder_level', 18, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 100);
            $table->string('name');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'code']);
        });

        Schema::create('stock_balances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity_on_hand', 18, 4)->default(0);
            $table->decimal('quantity_reserved', 18, 4)->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'inventory_item_id']);
        });

        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('performed_by_user_id')->constrained('users')->restrictOnDelete();
            $table->string('movement_type', 50);
            $table->decimal('quantity', 18, 4);
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('stock_reservations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->foreignId('reserved_by_user_id')->constrained('users')->restrictOnDelete();
            $table->decimal('quantity', 18, 4);
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('inventory_items');
    }
};
