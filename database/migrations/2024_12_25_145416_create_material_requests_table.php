<?php

use App\Enum\MaterialRequestStatus;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('hod_approval')->constrained('users');
            $table->foreignId('gm_approval')->constrained('users');
            // $table->string('description');
            // $table->integer('quantity');
            $table->string('document');
            $table->enum('status', array_map(fn($role) => $role->value, MaterialRequestStatus::cases()))->default(MaterialRequestStatus::Pending);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
