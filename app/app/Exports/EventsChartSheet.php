<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class EventsChartSheet implements WithTitle, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Gráfico';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insertar los datos manualmente
                $sheet->fromArray([
                    ['Tipo de Evento', 'Cantidad'],
                    ['Total de Eventos', $this->data['totalEvents']],
                    ['Eventos Próximos', $this->data['upcomingEvents']],
                    ['Eventos en Curso', $this->data['ongoingEvents']],
                    ['Eventos Pasados', $this->data['pastEvents']],
                ], null, 'A1');
                
                // Crear gráfico después de que los datos estén disponibles
                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Gráfico'!\$A\$2:\$A\$5", null, 4),
                ];
                
                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Gráfico'!\$B\$2:\$B\$5", null, 4),
                ];
                
                $series = new DataSeries(
                    DataSeries::TYPE_BARCHART,
                    DataSeries::GROUPING_STANDARD,
                    range(0, 3), // Rango fijo para evitar errores
                    [],
                    $dataSeriesLabels,
                    $dataSeriesValues
                );
                
                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_RIGHT, null, false);
                $title = new Title('Distribución de Eventos');
                
                $chart = new Chart(
                    'event_chart',
                    $title,
                    $legend,
                    $plotArea
                );
                
                $chart->setTopLeftPosition('D7');
                $chart->setBottomRightPosition('L20');
                
                $sheet->addChart($chart);
            }
        ];
    }
} 