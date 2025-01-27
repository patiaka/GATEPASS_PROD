<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use App\Models\MaterialRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enum\MaterialRequestStatus;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MaterialRequest::where('status', MaterialRequestStatus::Approved)
            ->chunk(100, function ($requests) {
                foreach ($requests as $row) {
                    DB::transaction(function () use ($row) {
                        // Verrouiller la ligne pour éviter les modifications concurrentes
                        $row = MaterialRequest::where('id', $row->id)->lockForUpdate()->first();
                        // Vérifier si la date de fin est dépassée
                        if ($row->expire_at < Carbon::now()) {
                            $row->update([
                                'status' => MaterialRequestStatus::Expired,
                            ]);
                        }
                        try {
                        } catch (\Exception $e) {
                            Log::error('Failed to update row', [
                                'row' => $row->trans_ref,
                                'error' => $e->getMessage(),
                            ]);
                            throw $e; // Annuler la transaction en cas d'erreur
                        }
                    });
                }
            });

        $this->info('Check-pay-by-link process completed.');
    }
}
