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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('title');
            $table->enum('type', ['training', 'match', 'event', 'meeting'])->default('training');
            $table->text('description')->nullable();
            
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->string('recurrence')->nullable(); // weekly, monthly, etc.
            $table->unsignedInteger('max_participants')->nullable();
            
            $table->string('color', 7)->default('#10B981'); // Pour le calendrier
            $table->text('notes')->nullable(); // Notes internes
            
            $table->timestamps();
            
            $table->index(['club_id', 'date']);
            $table->index(['club_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
