<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->string('contract_number', 100)->unique();
            $table->string('title');
            $table->text('scope')->nullable();
            $table->string('currency', 3)->default('AED');
            $table->decimal('contract_value', 18, 2)->default(0);
            $table->decimal('retention_percentage', 5, 2)->default(0);
            $table->decimal('advance_percentage', 5, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->text('payment_terms')->nullable();
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->foreignId('contract_id')
                ->nullable()
                ->after('client_id')
                ->constrained()
                ->nullOnDelete();
        });

        Schema::create('contract_amendments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 100);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('value_change', 18, 2)->default(0);
            $table->integer('time_change_days')->default(0);
            $table->date('effective_date')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamps();

            $table->unique(['contract_id', 'reference']);
        });

        Schema::create('contract_guarantees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('type', 100);
            $table->string('reference')->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });

        Schema::create('contract_insurances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('type', 150);
            $table->string('policy_number')->nullable();
            $table->string('provider')->nullable();
            $table->decimal('coverage_amount', 18, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_insurances');
        Schema::dropIfExists('contract_guarantees');
        Schema::dropIfExists('contract_amendments');

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('contract_id');
        });

        Schema::dropIfExists('contracts');
    }
};
