<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->foreignId('requested_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('approval_request_id')->nullable()->constrained('approval_requests')->nullOnDelete();
            $table->string('request_number', 100)->unique();
            $table->date('request_date');
            $table->date('required_by_date')->nullable();
            $table->text('purpose')->nullable();
            $table->string('currency', 3)->default('AED');
            $table->decimal('total_estimated_amount', 18, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('purchase_request_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('quantity', 18, 4);
            $table->string('unit', 50);
            $table->decimal('estimated_unit_price', 18, 4)->default(0);
            $table->decimal('estimated_total', 18, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('requests_for_quotation', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->restrictOnDelete();
            $table->string('rfq_number', 100)->unique();
            $table->date('issue_date');
            $table->date('closing_date')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamps();
        });

        Schema::create('request_for_quotation_supplier', function (Blueprint $table): void {
            $table->foreignId('request_for_quotation_id')->constrained('requests_for_quotation')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('status', 50)->default('invited');
            $table->primary(['request_for_quotation_id', 'supplier_id']);
        });

        Schema::create('supplier_quotations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('request_for_quotation_id')->constrained('requests_for_quotation')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->string('quotation_number', 100)->nullable();
            $table->date('quotation_date')->nullable();
            $table->string('currency', 3)->default('AED');
            $table->decimal('total_amount', 18, 2);
            $table->unsignedInteger('lead_time_days')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status', 50)->default('received');
            $table->timestamps();
        });

        Schema::create('procurement_evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_quotation_id')->constrained()->cascadeOnDelete();
            $table->decimal('technical_score', 5, 2)->nullable();
            $table->decimal('commercial_score', 5, 2)->nullable();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->boolean('recommended')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->restrictOnDelete();
            $table->foreignId('purchase_request_id')->constrained()->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->foreignId('approval_request_id')->nullable()->constrained('approval_requests')->nullOnDelete();
            $table->string('purchase_order_number', 100)->unique();
            $table->date('order_date');
            $table->string('currency', 3)->default('AED');
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->string('status', 50)->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('quantity', 18, 4);
            $table->string('unit', 50);
            $table->decimal('unit_price', 18, 4);
            $table->decimal('total', 18, 2);
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->restrictOnDelete();
            $table->string('delivery_number', 100);
            $table->date('delivery_date');
            $table->string('status', 50)->default('delivered');
            $table->timestamps();
        });

        Schema::create('goods_receipts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('received_by_user_id')->constrained('users')->restrictOnDelete();
            $table->string('receipt_number', 100)->unique();
            $table->date('receipt_date');
            $table->string('status', 50)->default('received');
            $table->timestamps();
        });

        Schema::create('supplier_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->restrictOnDelete();
            $table->string('invoice_number', 100);
            $table->date('invoice_date');
            $table->decimal('amount', 18, 2);
            $table->string('status', 50)->default('received');
            $table->string('payment_status', 50)->default('unpaid');
            $table->timestamps();

            $table->unique(['purchase_order_id', 'invoice_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
        Schema::dropIfExists('goods_receipts');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('procurement_evaluations');
        Schema::dropIfExists('supplier_quotations');
        Schema::dropIfExists('request_for_quotation_supplier');
        Schema::dropIfExists('requests_for_quotation');
        Schema::dropIfExists('purchase_request_items');
        Schema::dropIfExists('purchase_requests');
    }
};
