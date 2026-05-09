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
        Schema::table('material_requests', function (Blueprint $table) {
            // DIRECTOR : NO ACTION en DELETE et UPDATE
            $table->foreignId('director_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->text('director_comment')->nullable();
            $table->timestamp('director_approval_date')->nullable();
        });

        Schema::table('car_requests', function (Blueprint $table) {
            // DIRECTOR : NO ACTION en DELETE et UPDATE
            $table->foreignId('director_approval_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->text('director_comment')->nullable();
            $table->timestamp('director_approval_date')->nullable();
        });

        Schema::table('departments', function (Blueprint $table) {
            // DIRECTOR : NO ACTION en DELETE et UPDATE
            $table->foreignId('director_id')
                ->nullable()
                ->default(null)
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_requests', function (Blueprint $table) {
            $table->dropForeign(['director_approval_id']);
            $table->dropColumn(['director_approval_id', 'director_comment', 'director_approval_date']);
        });

        Schema::table('car_requests', function (Blueprint $table) {
            $table->dropForeign(['director_approval_id']);
            $table->dropColumn(['director_approval_id', 'director_comment', 'director_approval_date']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['director_id']);
            $table->dropColumn('director_id');
        });
    }
};
