<?php

declare(strict_types=1);

use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();

            // Aucune cascade (DELETE/UPDATE) pour éviter les multiple cascade paths
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            // La SEULE FK en cascade côté DELETE (SET NULL). UPDATE = NO ACTION
            $table->foreignId('gm_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('no action');

            $table->text('gm_comment')->nullable();
            $table->timestamp('gm_approval_date')->nullable();

            // Autre FK vers users : NO ACTION (pas de cascade)
            $table->foreignId('hod_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->text('hod_comment')->nullable();
            $table->timestamp('hod_approval_date')->nullable();

            $table->string('resident');
            $table->string('somisy_car');
            $table->string('car_type')->nullable();
            $table->string('car_number')->nullable();
            $table->string('comment')->nullable();
            $table->date('start');
            $table->date('end');
            $table->time('depart_at');
            $table->time('arrive_at');
            $table->string('destination');
            $table->string('company');
            $table->string('reason');
            $table->date('expire_at')->nullable();
            $table->string('status')->default(MaterialRequestStatus::Pending);
            $table->index('reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_requests');
    }
};
