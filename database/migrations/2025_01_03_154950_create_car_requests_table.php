<?php

use App\Enum\MaterialRequestStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('gm_approval_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('gm_comment')->nullable();
            $table->timestamp('gm_approval_date')->nullable();

            $table->foreignId('hod_approval_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('hod_comment')->nullable();
            $table->timestamp('hod_approval_date')->nullable();

            $table->string('resident');
            $table->string('expatriate');
            $table->string('car_type');
            $table->string('destination');
            $table->dateTime('depart_at');
            $table->dateTime('arrive_at');
            $table->enum('status', [
                'Pending',
                'Progress',
                'Rejected',
                'Approved'
            ])->default(MaterialRequestStatus::Pending);
            $table->index('reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_requests');
    }
};
