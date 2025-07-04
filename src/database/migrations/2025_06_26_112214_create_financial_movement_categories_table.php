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
        Schema::create('financial_movement_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('default_flow_type', ['in', 'out']);
            $table->string('color')->default('#3490dc');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_movement_categories');
    }
};
