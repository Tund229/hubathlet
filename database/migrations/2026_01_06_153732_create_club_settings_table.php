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
        Schema::create('club_settings', function (Blueprint $table) {
            $table->id();
    
            // Relation club
            $table->foreignId('club_id')
                  ->constrained()
                  ->cascadeOnDelete();
    
            // PWA / Application
            $table->string('app_name');              // Nom affiché à l'installation
            $table->string('app_short_name')->nullable();
            $table->string('app_icon')->nullable();  // Icône PWA
            $table->string('app_theme_color', 20)->nullable();
            $table->string('app_background_color', 20)->nullable();
    
            // Localisation
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('fr');
    
            // Fonctionnalités
            $table->boolean('push_notifications')->default(false);
            $table->boolean('public_registration')->default(true);
    
            $table->timestamps();
    
            // Un club = un seul settings
            $table->unique('club_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_settings');
    }
};
