<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CarRequest;
use Illuminate\Console\Command;

class NormalizeCarNumbers extends Command
{
    protected $signature = 'carnumbers:normalize {--dry-run : Affiche les changements sans rien modifier}';

    protected $description = 'Normalise les numéros de véhicule légers (type Lv) au format LV-<chiffres>.';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $fixed = 0;
        $skipped = [];

        if ($dry) {
            $this->warn('MODE DRY-RUN : aucune donnée ne sera modifiée.');
        }

        CarRequest::query()
            ->where('car_type', 'Lv')
            ->where('somisy_car', '!=', 'no_vehicle')
            ->whereNotNull('car_number')
            ->where('car_number', '!=', '')
            ->chunkById(200, function ($rows) use (&$fixed, &$skipped, $dry) {
                foreach ($rows as $request) {
                    $value = trim((string) $request->car_number);

                    // 1) Priorité au jeton « LV<chiffres> » présent dans la chaîne
                    //    (gère « BN6935 MD / LV241 » -> 241, « LV242 (BN..) » -> 242, « LV-278 » -> 278)
                    if (preg_match('/LV\s*-?\s*(\d+)/i', $value, $m)) {
                        $normalized = 'LV-'.$m[1];
                    }
                    // 2) Sinon, valeur composée uniquement de chiffres (± espaces)
                    elseif (preg_match('/^\s*(\d[\d\s]*)$/', $value, $m)) {
                        $normalized = 'LV-'.preg_replace('/\s+/', '', $m[1]);
                    }
                    // 3) Format non reconnu -> revue manuelle
                    else {
                        $skipped[] = "#{$request->id} : « {$value} »";

                        continue;
                    }

                    if ($normalized !== $request->car_number) {
                        $this->line("  #{$request->id} : « {$request->car_number} » -> « {$normalized} »");
                        if (! $dry) {
                            $request->updateQuietly(['car_number' => $normalized]);
                        }
                        $fixed++;
                    }
                }
            });

        $this->newLine();
        $this->info(($dry ? '[DRY-RUN] ' : '').$fixed.($dry ? ' entrée(s) seraient corrigées.' : ' entrée(s) corrigées.'));

        if ($skipped !== []) {
            $this->warn(count($skipped).' entrée(s) au format non reconnu (à revoir manuellement) :');
            foreach ($skipped as $line) {
                $this->line('  '.$line);
            }
        }

        return self::SUCCESS;
    }
}
