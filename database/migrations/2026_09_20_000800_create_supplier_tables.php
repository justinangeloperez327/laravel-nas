<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code', 100)->unique();
            $table->string('legal_name')->nullable();
            $table->string('trade_license_number')->nullable();
            $table->date('trade_license_expiry')->nullable();
            $table->string('tax_registration_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('status', 50)->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('supplier_category_supplier', function (Blueprint $table): void {
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['supplier_id', 'supplier_category_id']);
        });

        Schema::create('supplier_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('supplier_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 100);
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable()->index();
            $table->string('storage_provider', 50)->nullable();
            $table->string('external_file_id')->nullable();
            $table->string('storage_path')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_bank_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('account_name');
            $table->text('iban')->nullable();
            $table->text('swift_code')->nullable();
            $table->string('currency', 3)->default('AED');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('supplier_performance_evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price_score', 5, 2)->nullable();
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->decimal('delivery_score', 5, 2)->nullable();
            $table->decimal('responsiveness_score', 5, 2)->nullable();
            $table->text('comments')->nullable();
            $table->date('evaluated_on');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_performance_evaluations');
        Schema::dropIfExists('supplier_bank_accounts');
        Schema::dropIfExists('supplier_documents');
        Schema::dropIfExists('supplier_contacts');
        Schema::dropIfExists('supplier_category_supplier');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('supplier_categories');
    }
};
