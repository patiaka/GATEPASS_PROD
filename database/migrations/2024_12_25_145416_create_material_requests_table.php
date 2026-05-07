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
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->string('company');

            // AUTEUR : NO ACTION en DELETE et UPDATE
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->foreignId('person_out_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            // GM : SEULE contrainte en cascade (DELETE SET NULL), UPDATE = NO ACTION
            $table->foreignId('gm_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('no action');

            $table->text('gm_comment')->nullable();
            $table->timestamp('gm_approval_date')->nullable();

            // HOD : NO ACTION en DELETE et UPDATE
            $table->foreignId('hod_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->text('hod_comment')->nullable();
            $table->timestamp('hod_approval_date')->nullable();

            $table->date('expire_at')->nullable();

            $table->enum('status', [
                'Pending',
                'Progress',
                'Rejected',
                'Approved',
                'Expired',
            ])->default(MaterialRequestStatus::Pending);

            $table->timestamps();
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
