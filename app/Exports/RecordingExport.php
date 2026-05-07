<?php

declare(strict_types=1);

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

final class RecordingExport implements FromQuery, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(public $query, public string $type)
    {
        $this->query = $query;
        $this->type = $type;
    }

    public function query()
    {
        return $this->query;

    }

    public function map($data): array
    {
        if ($this->type === 'car') {
            return [
                $data->id,
                $data->requestable->reference,
                $data->created_at,
                $data->car_driver->name,
                $data->car_driver->department->name,
                $data->user->name,
                $data->requestable->company,
                $data->requestable->car_number,
                $data->requestable->car_type,
                $data->gate,
                $data->action,
                $data->decision,
            ];
        }

        return [
            $data->id,
            $data->requestable->reference,
            $data->created_at,
            $data->requestable->user->department->name,
            $data->user->name,
            $data->requestable->company,
            $data->requestable->person_out->name,
            $data->gate,
            $data->action,
            $data->decision,
        ];

    }

    public function headings(): array
    {
        if ($this->type === 'car') {
            return [
                'ID',
                'Reference',
                'Checked At',
                'Driver Name',
                'Driver Department',
                'Checked By',
                'Company',
                'Car Number',
                'Car Type',
                'Gate',
                'Action',
                'Decision',
            ];
        }

        return [
            'ID',
            'Reference',
            'Checked At',
            'User Department',
            'Checked By',
            'Company',
            'Person Out',
            'Gate',
            'Action',
            'Decision',
        ];
    }
}
