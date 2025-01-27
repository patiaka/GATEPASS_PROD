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

            $table->enum('somisy_car', ['Yes', 'No']);
            $table->enum('resident', ['Yes', 'No']);
            $table->enum('expatriate', ['Yes', 'No']);
            $table->enum('licence', ['Mali DL', 'Foreign DL', 'Intl Permit']);
            $table->enum('car_type', ['Lv', 'Bus', 'Truck']);
            $table->string('car_number');
            $table->date('start');
            $table->date('end');
            $table->time('depart_at');
            $table->time('arrive_at');
            $table->string('destination');
            $table->string('justification');
            $table->date('expire_at')->nullable();
            $table->enum('status', [
                'Pending',
                'Progress',
                'Rejected',
                'Approved',
                'Expired'
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
