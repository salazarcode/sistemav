<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EventsListSheet implements FromArray, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        if (!isset($this->data['eventsList']) || empty($this->data['eventsList'])) {
            return [['No hay eventos para mostrar']];
        }
        
        $rows = [];
        foreach ($this->data['eventsList'] as $event) {
            $rows[] = [
                $event['name'],
                $event['organization'],
                $event['participants'],
                $event['location'],
                $event['categories'],
                $event['dates']
            ];
        }
        
        return $rows;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Lista de Eventos';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Evento',
            'Organización',
            'Participantes',
            'Ubicación',
            'Categorías',
            'Fechas'
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Calcular el número total de filas
        $rowCount = count($this->array()) + 1;
        $lastColumn = 'F'; // Última columna (Fechas)
        
        // Estilo para toda la hoja
        $sheet->getStyle('A1:' . $lastColumn . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Estilo para la cabecera
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4E73DF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Alineación de celdas específicas
        $sheet->getStyle('C2:C' . $rowCount)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        // Ajustar contenido de columnas con texto largo
        $sheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    }
}
