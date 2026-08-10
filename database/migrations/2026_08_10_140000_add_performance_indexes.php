<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index de performance sur les colonnes fréquemment filtrées.
     * NB : recordings(requestable_type, requestable_id) est déjà indexé par
     * morphs() — on ne le recrée pas. reference est déjà indexé.
     */
    public function up(): void
    {
        Schema::table('car_requests', function (Blueprint $table) {
            $table->index('status');
            $table->index('next_approver_role');
            $table->index('user_id');
        });

        Schema::table('material_requests', function (Blueprint $table) {
            $table->index('status');
            $table->index('next_approver_role');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('car_requests', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['next_approver_role']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('material_requests', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['next_approver_role']);
            $table->dropIndex(['user_id']);
        });
    }
};
