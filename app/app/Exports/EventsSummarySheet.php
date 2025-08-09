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

class EventsSummarySheet implements FromArray, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
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
        $rows = [];
        
        // 1. Datos de distribución general de eventos
        $rows[] = ['Total de Eventos', $this->data['totalEvents']];
        $rows[] = ['Eventos Próximos', $this->data['upcomingEvents']];
        $rows[] = ['Eventos en Curso', $this->data['ongoingEvents']];
        $rows[] = ['Eventos Pasados', $this->data['pastEvents']];
        
        // Espacio en blanco
        $rows[] = ['', ''];
        $rows[] = ['', ''];
        
        // 2. Tasa de asistencia general
        if (isset($this->data['attendanceRate'])) {
            $rows[] = ['Tasa de Asistencia', $this->data['attendanceRate'] . '%'];
        }
        
        // Espacio en blanco
        $rows[] = ['', ''];
        $rows[] = ['', ''];
        
        // 3. Eventos por categoría (si está disponible)
        if (isset($this->data['eventsByCategory']) && !empty($this->data['eventsByCategory'])) {
            $rows[] = ['Eventos por Categoría', ''];
            
            foreach ($this->data['eventsByCategory'] as $category) {
                $rows[] = [$category['name'], $category['count']];
            }
            
            // Espacio en blanco
            $rows[] = ['', ''];
            $rows[] = ['', ''];
        }
        
        // 4. Eventos por institución (Top 5)
        if (isset($this->data['eventsByInstitution']) && !empty($this->data['eventsByInstitution'])) {
            $rows[] = ['Eventos por Institución (Top 5)', ''];
            
            $count = 0;
            foreach ($this->data['eventsByInstitution'] as $institution) {
                if ($count < 5) {
                    $rows[] = [$institution['name'], $institution['count']];
                    $count++;
                } else {
                    break;
                }
            }
            
            // Espacio en blanco
            $rows[] = ['', ''];
            $rows[] = ['', ''];
        }
        
        // 5. Participantes por género
        if (isset($this->data['participantsByGender']) && !empty($this->data['participantsByGender'])) {
            $rows[] = ['Participantes por Género', ''];
            
            foreach ($this->data['participantsByGender'] as $gender) {
                $rows[] = [$gender['name'], $gender['count']];
            }
            
            // Espacio en blanco
            $rows[] = ['', ''];
            $rows[] = ['', ''];
        }
        
        // 6. Participantes por edad
        if (isset($this->data['participantsByAge']) && !empty($this->data['participantsByAge'])) {
            $rows[] = ['Participantes por Edad', ''];
            
            foreach ($this->data['participantsByAge'] as $age) {
                $rows[] = [$age['name'], $age['count']];
            }
            
            // Espacio en blanco
            $rows[] = ['', ''];
            $rows[] = ['', ''];
        }

        // 7. Participantes por nivel educativo (si está disponible)
        if (isset($this->data['participantsByEducation']) && !empty($this->data['participantsByEducation'])) {
            $rows[] = ['Participantes por Nivel Educativo', ''];
            
            foreach ($this->data['participantsByEducation'] as $education) {
                $rows[] = [$education['name'], $education['count']];
            }
        }
        
        return $rows;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Resumen de Eventos';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Descripción',
            'Cantidad',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Calcular el número total de filas
        $rowCount = count($this->array()) + 1;
        
        // Estilo para toda la hoja
        $sheet->getStyle('A1:B' . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Estilo para la cabecera
        $sheet->getStyle('A1:B1')->applyFromArray([
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
        
        // Encontrar las filas de sección
        $sectionRows = [];
        $currentRow = 1;
        for ($i = 0; $i < count($this->array()); $i++) {
            $row = $this->array()[$i];
            $currentRow++;
            
            // Si encontramos una fila con una celda vacía y luego otra con contenido, es una sección
            if ($i < count($this->array()) - 1 && 
                empty($row[0]) && 
                !empty($this->array()[$i + 1][0]) && 
                empty($this->array()[$i + 1][1])) {
                $sectionRows[] = $currentRow + 1;
            }
        }
        
        // Estilo para las filas de sección (encabezados de categoría)
        // Incluir manualmente las filas clave conocidas
        $knownSectionRows = [7, 11];
        $sectionRows = array_unique(array_merge($sectionRows, $knownSectionRows));
        
        foreach ($sectionRows as $row) {
            if ($row <= $rowCount) {
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F0FE'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Establecer altura de la fila para encabezados de sección
                $sheet->getRowDimension($row)->setRowHeight(22);
            }
        }
        
        // Estilo para las primeras filas (datos principales)
        $sheet->getStyle('A2:A5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8F9FC'],
            ],
        ]);
        
        // Estilo para la fila de tasa de asistencia
        if (isset($this->data['attendanceRate'])) {
            $attendanceRow = 7;
            $sheet->getStyle('A' . $attendanceRow . ':B' . $attendanceRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FC'],
                ],
            ]);
        }
        
        // Alineación de números a la derecha con formato
        $sheet->getStyle('B2:B' . $rowCount)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'font' => [
                'size' => 11,
            ],
        ]);
        
        // Establecer altura de las filas para mejor espaciado
        for ($i = 1; $i <= $rowCount; $i++) {
            if (!in_array($i, $sectionRows)) {
                $sheet->getRowDimension($i)->setRowHeight(18);
            }
        }
        
        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        
        // Formatear los números con separadores de miles
        // Recorrer las filas y aplicar formato numérico a las celdas con números
        for ($row = 2; $row <= $rowCount; $row++) {
            $cellValue = $sheet->getCell('B' . $row)->getValue();
            
            // Si es un número (y no un porcentaje que ya tiene formato especial)
            if (is_numeric($cellValue) && strpos($cellValue, '%') === false) {
                // Aplicar formato de número con separador de miles y decimales (2 decimales)
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            }
        }
        
        return [];
    }
} 