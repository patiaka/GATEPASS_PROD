<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type');           // CarRequest | MaterialRequest
            $table->unsignedBigInteger('subject_id')->index();
            $table->string('subject_ref')->nullable(); // référence lisible
            $table->string('event');                   // created | updated | deleted
            $table->unsignedBigInteger('causer_id')->nullable()->index();
            $table->string('causer_name')->nullable();
            $table->json('changes')->nullable();       // champs modifiés
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
