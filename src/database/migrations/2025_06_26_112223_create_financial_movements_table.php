<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('financial_movement_categories');
            $table->foreignId('contact_id')->nullable()->constrained('contacts');

            $table->enum('flow_type', ['in', 'out']);
            $table->decimal('amount', 15, 2);
            $table->date('operation_date');
            $table->string('reference')->nullable();
            $table->enum('payment_method', ['cash', 'check', 'transfer', 'card']);
            $table->text('description')->nullable();

            // Pour lier à des documents
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_financiers');
    }
};
