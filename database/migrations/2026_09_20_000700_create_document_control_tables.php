<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 150)->unique();
            $table->string('title');
            $table->string('document_type', 100);
            $table->string('discipline', 100)->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('document_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->string('revision_code', 50);
            $table->string('status', 50)->default('draft')->index();
            $table->string('storage_provider', 50)->nullable();
            $table->string('external_file_id')->nullable();
            $table->string('storage_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('checksum', 128)->nullable();
            $table->foreignId('created_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('decided_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_comments')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'revision_code']);
        });

        Schema::create('transmittals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('transmittal_number', 150)->unique();
            $table->string('direction', 20);
            $table->string('party_name');
            $table->string('subject');
            $table->date('transmittal_date');
            $table->string('status', 50)->default('issued');
            $table->timestamps();
        });

        Schema::create('transmittal_document_revision', function (Blueprint $table): void {
            $table->foreignId('transmittal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_revision_id')->constrained()->cascadeOnDelete();
            $table->primary(['transmittal_id', 'document_revision_id']);
        });

        Schema::create('correspondences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_number', 150)->unique();
            $table->string('direction', 20);
            $table->string('party_name');
            $table->string('subject');
            $table->text('summary')->nullable();
            $table->date('correspondence_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correspondences');
        Schema::dropIfExists('transmittal_document_revision');
        Schema::dropIfExists('transmittals');
        Schema::dropIfExists('document_revisions');
        Schema::dropIfExists('documents');
    }
};
