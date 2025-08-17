<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventsExport implements WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new EventsSummarySheet($this->data),
            new EventsListSheet($this->data),
            new EventsChartImageSheet($this->data),
        ];
    }
} 