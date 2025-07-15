<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            // $table->string('type'); // client, fournisseur, ouvrier, architecte...
            $table->enum('type', ['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre'])->default('client');
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('siret')->nullable();
            $table->json('metadata')->nullable(); // Pour infos spécifiques
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
