<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table pivot pour le multi-rôles.
     *
     * On conserve les colonnes users.role et users.delegated_role pendant la
     * transition (rétrocompatibilité + rollback). Cette table devient la
     * source de vérité pour les rôles.
     */
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->timestamps();

            $table->unique(['user_id', 'role']);
        });

        // Backfill : recopier les rôles existants (role + delegated_role) dans le pivot
        DB::table('users')->select('id', 'role', 'delegated_role')->orderBy('id')->chunk(200, function ($users) {
            $now = now();
            $rows = [];
            foreach ($users as $u) {
                $roles = collect([$u->role, $u->delegated_role])
                    ->filter()
                    ->unique();
                foreach ($roles as $role) {
                    $rows[] = [
                        'user_id' => $u->id,
                        'role' => $role,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            if ($rows) {
                DB::table('role_user')->insert($rows);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
