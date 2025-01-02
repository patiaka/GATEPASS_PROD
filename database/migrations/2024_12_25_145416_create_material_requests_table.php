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
            $table->string('reference')->nullable()->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('gm_approval_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('gm_comment')->nullable();
            $table->timestamp('gm_approval_date')->nullable();

            $table->foreignId('hod_approval_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('hod_comment')->nullable();
            $table->timestamp('hod_approval_date')->nullable();

            $table->enum('status', array_map(fn($status) => $status->value, MaterialRequestStatus::cases()))->default(MaterialRequestStatus::Pending);
            $table->timestamps();
            $table->index('reference');
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
