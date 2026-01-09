<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Table pivot pour associer les utilisateurs aux clubs avec leur rôle
     * Un utilisateur peut avoir différents rôles dans différents clubs
     */
    public function up(): void
    {
        Schema::create('club_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            
            // Informations supplémentaires du membre dans le club
            $table->string('jersey_number')->nullable();      // Numéro de maillot
            $table->string('position')->nullable();           // Position (pour sports d'équipe)
            $table->string('license_number')->nullable();     // Numéro de licence
            $table->date('joined_at')->nullable();            // Date d'adhésion au club
            $table->date('license_expires_at')->nullable();   // Expiration licence
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending');
            $table->text('notes')->nullable();                // Notes internes
            
            $table->timestamps();
            
            // Un utilisateur ne peut avoir qu'un seul rôle par club
            $table->unique(['club_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_user');
    }
};
