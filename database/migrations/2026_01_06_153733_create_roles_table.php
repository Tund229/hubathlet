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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Nom affiché (ex: "Propriétaire")
            $table->string('slug')->unique(); // Identifiant unique (ex: "owner")
            $table->string('description')->nullable();
            $table->string('color', 20)->default('#6B7280'); // Couleur pour l'UI
            $table->integer('level')->default(0); // Niveau hiérarchique (plus haut = plus de droits)
            $table->boolean('is_system')->default(false); // Rôle système non modifiable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
