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

            $table->enum('somisy_car', ['Yes', 'No']);
            $table->enum('resident', ['Yes', 'No']);
            $table->enum('expatriate', ['Yes', 'No', 'Escort']);
            $table->enum('licence', ['Mali DL', 'Foreign DL', 'Intl Permit']);
            $table->enum('car_type', ['Lv', 'Bus', 'Truck']);
            $table->string('car_number');
            $table->date('start');
            $table->date('end');
            $table->time('depart_at');
            $table->time('arrive_at');
            $table->string('route');
            $table->string('destination');
            $table->string('company');
            $table->string('reason');
            $table->date('expire_at')->nullable();
            $table->enum('status', [
                'Pending',
                'Progress',
                'Rejected',
                'Approved',
                'Expired',
            ])->default(MaterialRequestStatus::Pending);

            $table->index('reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_requests');
    }
};
