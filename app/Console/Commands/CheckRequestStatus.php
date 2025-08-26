<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use App\Models\MaterialRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Log;

class CheckRequestStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-request-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire approved requests when their end date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->expireRequests(MaterialRequest::class, 'expire_at');
        $this->expireRequests(CarRequest::class, 'end');

        $this->info('Expired requests have been updated successfully.');
    }

    /**
     * Expire approved requests for a given model.
     */
    protected function expireRequests(string $modelClass, string $dateField): void
    {
        $modelClass::where('status', MaterialRequestStatus::Approved)
            ->chunk(100, function ($requests) use ($modelClass, $dateField) {
                foreach ($requests as $row) {
                    DB::transaction(function () use ($row, $modelClass, $dateField) {
                        try {
                            // Lock row to avoid race conditions
                            $row = $modelClass::where('id', $row->id)->lockForUpdate()->first();

                            // Expire if past date
                            if ($row->{$dateField} < Carbon::now()) {
                                $row->update(['status' => MaterialRequestStatus::Expired]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to expire request', [
                                'model' => $modelClass,
                                'row'   => $row->trans_ref ?? $row->id,
                                'error' => $e->getMessage(),
                            ]);
                            throw $e; // rollback transaction
                        }
                    });
                }
            });
    }
}
