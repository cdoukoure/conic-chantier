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

        Schema::create('project_contact', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            // $table->string('role'); // client_principal, ouvrier, sous-traitant...
            $table->enum('role', ['fournisseur', 'prestataire', 'ouvrier', 'autre'])->default('ouvrier');
            $table->decimal('hourly_rate', 10, 2)->nullable(); // Pour les ouvriers
            $table->primary(['project_id', 'contact_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
