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

class ParticipantsSheet implements FromArray, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
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
        $participants = $this->data['participants'];
        
        foreach ($participants as $participant) {
            $row = [];

            // Datos básicos
            $row[] = $participant->id;
            $row[] = $participant->personalData ? $participant->personalData->name : $participant->name;
            $row[] = $participant->personalData ? $participant->personalData->last_name : $participant->last_name;
            $row[] = $participant->personalData ? $participant->personalData->phone : $participant->phone;
            $row[] = $participant->email;
            $row[] = $participant->personalData ? $participant->personalData->dni : $participant->dni;
            $row[] = $participant->personalData ? $participant->personalData->type_dni : '';
            $row[] = $participant->personalData ? $participant->personalData->address : $participant->address;
            $row[] = $participant->personalData ? $participant->personalData->sex : $participant->gender;
            $row[] = $participant->personalData ? $participant->personalData->age : $participant->age;
            
            // Estado de asistencia
            $row[] = $participant->assist ? 'Sí' : 'No';
            
            // Fecha de registro
            $row[] = $participant->created_at ? $participant->created_at->format('d/m/Y H:i') : '';
            
            $rows[] = $row;
        }
        
        return $rows;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Lista de Participantes';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Teléfono',
            'Email',
            'Identificación',
            'Tipo ID',
            'Dirección',
            'Género',
            'Edad',
            'Asistencia',
            'Fecha de Registro'
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Calcular el número total de filas
        $rowCount = count($this->array()) + 1;
        $colCount = count($this->headings());
        $lastColumn = chr(64 + $colCount); // Convertir número a letra (A, B, C, ...)
        
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
        
        // Alineación de todas las celdas al centro
        $sheet->getStyle('A2:' . $lastColumn . $rowCount)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Ajustar la altura de la fila de cabecera
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Agregar filtro a las cabeceras
        $sheet->setAutoFilter('A1:' . $lastColumn . '1');
        
        // Congelar la primera fila
        $sheet->freezePane('A2');
    }
} 