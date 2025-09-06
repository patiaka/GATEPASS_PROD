<?php

namespace App\Exports;

use App\Models\Recording;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class RecordingExport implements FromQuery, Responsable, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct(public string $by_date)
    {
        $this->by_date = $by_date;
    }

    public function query()
    {
        return Recording::query()->whereDate('created_at', $this->by_date);
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->requestable->reference,
            $data->created_at,
            $data->user->department->name,
            $data->user->name,
            $data->requestable->company,
            $data->requestable->car_number,
            $data->requestable->car_type,
            $data->action,
            $data->decision,
        ];
    }

    public function headings(): array
    {
        return [
            'id',
            'requestable_reference',
            'date',
            'department',
            'user_name',
            'company',
            'car_number',
            'car_type',
            'action',
            'decision',
        ];
    }
}
