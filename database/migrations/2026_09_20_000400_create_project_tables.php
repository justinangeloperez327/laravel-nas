<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('business_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('project_number', 100)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 50)->default('planned')->index();
            $table->date('start_date')->nullable();
            $table->date('planned_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->decimal('contract_value', 18, 2)->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('project_milestones', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamps();
        });

        Schema::create('project_risks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('probability', 20)->nullable();
            $table->string('impact', 20)->nullable();
            $table->string('status', 50)->default('open');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('project_issues', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority', 20)->nullable();
            $table->string('status', 50)->default('open');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_issues');
        Schema::dropIfExists('project_risks');
        Schema::dropIfExists('project_milestones');
        Schema::dropIfExists('projects');
    }
};
