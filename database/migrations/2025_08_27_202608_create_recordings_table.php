<?php

declare(strict_types=1);

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
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete(); // chef de sécurité
            // Polymorphic relation pour MaterialRequest / CarRequest
            $table->morphs('requestable'); // requestable_id + requestable_type
            $table->string('action');      // entrée / sortie
            $table->string('decision'); // validée / rejetée
            $table->timestamp('checked_at');                // date et heure de la vérification
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
