<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

final class OffsiteSheet implements FromArray, WithHeadings, WithTitle
{
    /**
     * @param  array<int, string>  $sheetHeadings
     * @param  array<int, array<int, mixed>>  $rows
     */
    public function __construct(
        private string $sheetTitle,
        private array $sheetHeadings,
        private array $rows,
    ) {}

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->sheetHeadings;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
