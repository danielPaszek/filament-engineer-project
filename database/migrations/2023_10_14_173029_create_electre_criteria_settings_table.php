<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('electre_criteria_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electre_one_id')->constrained();
            $table->foreignId('criterion_id')->constrained();
            $table->double('weight');
            $table->double('q');
            $table->double('p');
            $table->double('v');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electre_criteria_settings');
    }
};
