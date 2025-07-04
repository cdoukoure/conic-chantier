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
        
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            // $table->string('type'); // client, fournisseur, ouvrier, architecte...
            $table->enum('type', ['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre'])->default('client');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('siret')->nullable();
            $table->json('metadata')->nullable(); // Pour infos spécifiques
            $table->timestamps();
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
