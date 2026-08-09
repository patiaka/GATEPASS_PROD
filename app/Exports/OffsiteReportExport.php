<?php

declare(strict_types=1);

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

final class OffsiteReportExport implements Responsable, WithMultipleSheets
{
    use Exportable;

    /**
     * @param  array<string, array{headings: array<int, string>, rows: array<int, array<int, mixed>>}>  $sheets
     */
    public function __construct(private array $sheets) {}

    public function sheets(): array
    {
        $out = [];
        foreach ($this->sheets as $title => $sheet) {
            $out[] = new OffsiteSheet($title, $sheet['headings'], $sheet['rows']);
        }

        return $out;
    }
}
