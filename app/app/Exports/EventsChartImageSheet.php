<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventsChartImageSheet implements WithTitle, WithDrawings
{
    protected $data;
    protected $chartImagePaths = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        // Generar las imágenes de los gráficos al crear la instancia
        $this->generateChartImages();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Gráficos';
    }

    /**
     * Genera y descarga las imágenes de los gráficos desde QuickChart.io
     */
    private function generateChartImages()
    {
        // Si tenemos imágenes de gráficos capturadas del navegador, usarlas
        if (isset($this->data['chartImages']) && !empty($this->data['chartImages'])) {
            foreach ($this->data['chartImages'] as $chartName => $chartImageData) {
                // Decodificar la imagen base64 y guardarla como archivo temporal
                $this->saveBase64ImageAsFile($chartImageData, $chartName);
            }
            return;
        }
        
        // Si no hay imágenes capturadas, usar el método antiguo
        // Ya no generamos la gráfica de distribución general
        // $this->generateEventDistributionChart();
        
        // 1. Gráfico de eventos por categoría (si está disponible)
        if (isset($this->data['eventsByCategory']) && !empty($this->data['eventsByCategory'])) {
            $this->generateEventsByCategoryChart();
        }
        
        // 2. Gráfico de eventos por mes (si está disponible)
        if (isset($this->data['eventsByMonth']) && !empty($this->data['eventsByMonth'])) {
            $this->generateEventsByMonthChart();
        }
        
        // 3. Gráfico de eventos por institución (si está disponible)
        if (isset($this->data['eventsByInstitution']) && !empty($this->data['eventsByInstitution'])) {
            $this->generateEventsByInstitutionChart();
        }
        
        // 4. Gráfico de participantes por género (si está disponible)
        if (isset($this->data['participantsByGender']) && !empty($this->data['participantsByGender'])) {
            $this->generateParticipantsByGenderChart();
        }
        
        // 5. Gráfico de participantes por edad (si está disponible)
        if (isset($this->data['participantsByAge']) && !empty($this->data['participantsByAge'])) {
            $this->generateParticipantsByAgeChart();
        }
        
        // 6. Gráfico de eventos con más participantes (si está disponible)
        if (isset($this->data['eventsByParticipants']) && !empty($this->data['eventsByParticipants'])) {
            $this->generateEventsWithMostParticipantsChart();
        }
        
        // 7. Gráfico de tasa de asistencia por categoría (si está disponible)
        if (isset($this->data['attendanceRateByCategory']) && !empty($this->data['attendanceRateByCategory'])) {
            $this->generateAttendanceRateByCategoryChart();
        }
        
        // 8. Gráfico de participantes por institución (si está disponible)
        if (isset($this->data['participantsByInstitution']) && !empty($this->data['participantsByInstitution'])) {
            $this->generateParticipantsByInstitutionChart();
        }
        
        // 9. Gráfico de participantes por nivel educativo (si está disponible)
        if (isset($this->data['participantsByEducation']) && !empty($this->data['participantsByEducation'])) {
            $this->generateParticipantsByEducationChart();
        }
    }

    /**
     * Genera el gráfico de distribución general de eventos
     */
    private function generateEventDistributionChart()
    {
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Total', 'Próximos', 'En Curso', 'Pasados'],
                'datasets' => [
                    [
                        'label' => 'Cantidad de Eventos',
                        'backgroundColor' => ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
                        'data' => [
                            $this->data['totalEvents'], 
                            $this->data['upcomingEvents'], 
                            $this->data['ongoingEvents'], 
                            $this->data['pastEvents']
                        ]
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Distribución de Eventos',
                    'fontSize' => 18
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'bottom'
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold'
                        ],
                        'formatter' => "function(value) { return value; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'event_distribution');
    }

    /**
     * Genera el gráfico de eventos por categoría
     */
    private function generateEventsByCategoryChart()
    {
        $categories = array_column($this->data['eventsByCategory'], 'name');
        $counts = array_column($this->data['eventsByCategory'], 'count');
        
        // Generar colores diferentes para cada categoría
        $colors = $this->generateColors(count($categories));
        
        $chartConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => $categories,
                'datasets' => [
                    [
                        'data' => $counts,
                        'backgroundColor' => $colors
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Eventos por Categoría',
                    'fontSize' => 18
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'right'
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value, context) { return context.chart.data.labels[context.dataIndex] + '\\n' + value + ' eventos'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'events_by_category');
    }

    /**
     * Genera el gráfico de eventos por mes
     */
    private function generateEventsByMonthChart()
    {
        $months = array_column($this->data['eventsByMonth'], 'month');
        $counts = array_column($this->data['eventsByMonth'], 'count');
        
        // Traducir nombres de meses si están en inglés
        $monthTranslations = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre',
            'Jan' => 'Ene',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
            'Nov' => 'Nov',
            'Dec' => 'Dic'
        ];
        
        $translatedMonths = [];
        foreach ($months as $month) {
            $translatedMonths[] = $monthTranslations[$month] ?? $month;
        }
        
        $chartConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $translatedMonths,
                'datasets' => [
                    [
                        'label' => 'Eventos',
                        'data' => $counts,
                        'borderColor' => '#4e73df',
                        'backgroundColor' => 'rgba(78, 115, 223, 0.1)',
                        'fill' => true,
                        'tension' => 0.4
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Eventos por Mes',
                    'fontSize' => 18
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Cantidad de Eventos'
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Mes'
                            ]
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'align' => 'top',
                        'color' => '#4e73df',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value > 0 ? value + ' eventos' : ''; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'events_by_month');
    }

    /**
     * Genera el gráfico de eventos por institución
     */
    private function generateEventsByInstitutionChart()
    {
        // Limitar a 10 instituciones para mejor visualización
        $data = array_slice($this->data['eventsByInstitution'], 0, 10);
        
        $institutions = array_column($data, 'name');
        $counts = array_column($data, 'count');
        
        // Generar colores diferentes para cada institución
        $colors = $this->generateColors(count($institutions));
        
        $chartConfig = [
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $institutions,
                'datasets' => [
                    [
                        'label' => 'Eventos',
                        'data' => $counts,
                        'backgroundColor' => $colors
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Eventos por Institución',
                    'fontSize' => 18
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Cantidad de Eventos'
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'display' => false
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'align' => 'end',
                        'anchor' => 'end',
                        'color' => '#000000',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value + ' eventos'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'events_by_institution');
    }

    /**
     * Genera el gráfico de participantes por género
     */
    private function generateParticipantsByGenderChart()
    {
        $gender = array_column($this->data['participantsByGender'], 'name');
        $counts = array_column($this->data['participantsByGender'], 'count');
        
        $colors = ['#4e73df', '#e74a3b', '#1cc88a', '#f6c23e'];
        
        $chartConfig = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $gender,
                'datasets' => [
                    [
                        'data' => $counts,
                        'backgroundColor' => $colors
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Participantes por Género',
                    'fontSize' => 18
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'right'
                ],
                'cutoutPercentage' => 70,
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value, context) { return context.chart.data.labels[context.dataIndex] + '\\n' + value + ' participantes'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'participants_by_gender');
    }

    /**
     * Genera el gráfico de participantes por edad
     */
    private function generateParticipantsByAgeChart()
    {
        $ageRanges = array_column($this->data['participantsByAge'], 'name');
        $counts = array_column($this->data['participantsByAge'], 'count');
        
        $colors = $this->generateColors(count($ageRanges));
        
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $ageRanges,
                'datasets' => [
                    [
                        'label' => 'Participantes',
                        'data' => $counts,
                        'backgroundColor' => $colors
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Participantes por Edad',
                    'fontSize' => 18
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Cantidad de Participantes'
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Rango de Edad'
                            ]
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value + ' participantes'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'participants_by_age');
    }
    
    /**
     * Genera el gráfico de eventos con más participantes
     */
    private function generateEventsWithMostParticipantsChart()
    {
        // Limitar a los 8 eventos con más participantes
        $data = array_slice($this->data['eventsByParticipants'], 0, 8);
        
        $eventNames = array_column($data, 'name');
        $participantCounts = array_column($data, 'count');
        
        $chartConfig = [
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $eventNames,
                'datasets' => [
                    [
                        'label' => 'Participantes',
                        'data' => $participantCounts,
                        'backgroundColor' => '#36b9cc',
                        'borderColor' => '#2c9fad'
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Eventos con Más Participantes',
                    'fontSize' => 18
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Cantidad de Participantes'
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'display' => false
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'align' => 'end',
                        'anchor' => 'end',
                        'color' => '#000000',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value + ' participantes'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'events_with_most_participants');
    }
    
    /**
     * Genera el gráfico de tasa de asistencia por categoría
     */
    private function generateAttendanceRateByCategoryChart()
    {
        $categories = array_column($this->data['attendanceRateByCategory'], 'name');
        $rates = array_column($this->data['attendanceRateByCategory'], 'rate');
        
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $categories,
                'datasets' => [
                    [
                        'label' => 'Tasa de Asistencia (%)',
                        'data' => $rates,
                        'backgroundColor' => '#1cc88a',
                        'borderColor' => '#17a673'
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Tasa de Asistencia por Categoría',
                    'fontSize' => 18
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                'max' => 100
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Porcentaje de Asistencia'
                            ]
                        ]
                    ],
                    'xAxes' => [
                        [
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Categoría'
                            ]
                        ]
                    ]
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value + '%'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'attendance_rate_by_category');
    }

    /**
     * Genera el gráfico de participantes por institución
     */
    private function generateParticipantsByInstitutionChart()
    {
        // Limitar a 10 instituciones para mejor visualización
        $data = array_slice($this->data['participantsByInstitution'], 0, 10);
        
        $institutions = array_column($data, 'name');
        $counts = array_column($data, 'count');
        
        // Generar colores diferentes para cada institución
        $colors = $this->generateColors(count($institutions));
        
        $chartConfig = [
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $institutions,
                'datasets' => [
                    [
                        'label' => 'Participantes',
                        'data' => $counts,
                        'backgroundColor' => $colors
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Participantes por Institución',
                    'fontSize' => 18
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true
                            ],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Cantidad de Participantes'
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'display' => false
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'align' => 'end',
                        'anchor' => 'end',
                        'color' => '#000000',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value) { return value + ' participantes'; }"
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'participants_by_institution');
    }

    /**
     * Genera el gráfico de participantes por nivel educativo
     */
    private function generateParticipantsByEducationChart()
    {
        $educationData = $this->data['participantsByEducation'];
        
        $chartConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => array_column($educationData, 'name'),
                'datasets' => [
                    [
                        'data' => array_column($educationData, 'count'),
                        'backgroundColor' => $this->generateColors(count($educationData))
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => 'Participantes por Nivel Educativo',
                    'fontSize' => 18
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'right'
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14
                        ],
                        'formatter' => "function(value, context) { return context.chart.data.labels[context.dataIndex] + '\\n' + value + ' participantes'; }"
                    ]
                ],
                'layout' => [
                    'padding' => [
                        'left' => 50,
                        'right' => 50,
                        'top' => 50,
                        'bottom' => 50
                    ]
                ]
            ]
        ];

        $this->downloadChartImage($chartConfig, 'participants_by_education');
    }

    /**
     * Genera colores aleatorios para los gráficos
     */
    private function generateColors($count)
    {
        $baseColors = [
            '#4e73df', // Azul
            '#1cc88a', // Verde
            '#f6c23e', // Amarillo
            '#e74a3b', // Rojo
            '#36b9cc', // Cyan
            '#6f42c1', // Morado
            '#5a5c69', // Gris
            '#fd7e14', // Naranja
            '#20c9a6', // Verde Lima
            '#858796', // Gris Claro
        ];
        
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            if ($i < count($baseColors)) {
                $colors[] = $baseColors[$i];
            } else {
                // Generar colores adicionales si necesitamos más
                $r = mt_rand(100, 240);
                $g = mt_rand(100, 240);
                $b = mt_rand(100, 240);
                $colors[] = "rgb($r, $g, $b)";
            }
        }
        
        return $colors;
    }

    /**
     * Descarga una imagen de gráfico desde QuickChart.io
     */
    private function downloadChartImage($chartConfig, $chartName)
    {
        // Añadir configuración global para todos los gráficos
        $chartConfig['options'] = array_merge([
            'layout' => [
                'padding' => [
                    'left' => 50,
                    'right' => 50,
                    'top' => 50,
                    'bottom' => 50
                ]
            ],
            'plugins' => [
                'datalabels' => [
                    'display' => true,
                    'color' => 'white',
                    'font' => [
                        'weight' => 'bold',
                        'size' => 14
                    ],
                    'padding' => 8,
                    'textAlign' => 'center'
                ]
            ]
        ], $chartConfig['options'] ?? []);

        // Convertir la configuración a JSON
        $chartConfigJson = json_encode($chartConfig);
        
        // Crear URL de la API de QuickChart
        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode($chartConfigJson) . '&width=800&height=450&devicePixelRatio=1.5&plugins=datalabels';
        
        // Crear un nombre de archivo único para la imagen temporal
        $tempFileName = $chartName . '_' . Str::random(6) . '.png';
        $chartImagePath = storage_path('app/public/' . $tempFileName);
        
        // Descargar la imagen usando Guzzle a través de la facade Http
        $response = Http::get($chartUrl);
        
        // Asegurar que el directorio exista
        if (!Storage::exists('public')) {
            Storage::makeDirectory('public');
        }
        
        // Guardar la imagen
        file_put_contents($chartImagePath, $response->body());
        
        // Almacenar la ruta de la imagen
        $this->chartImagePaths[] = [
            'path' => $chartImagePath,
            'name' => $chartName
        ];
    }

    /**
     * @return array
     */
    public function drawings()
    {
        $drawings = [];
        $startRow = 2; // Empezar desde la fila 2 para dejar espacio
        
        // Mapeo de nombres de archivos a nombres más descriptivos para los títulos
        $chartTitles = [
            'events_by_category' => 'Eventos por Categoría',
            'events_by_institution' => 'Eventos por Institución',
            'events_by_month' => 'Eventos por Mes',
            'events_with_most_participants' => 'Eventos con Más Participantes',
            'participants_by_gender' => 'Participantes por Género',
            'participants_by_age' => 'Participantes por Edad',
            'participants_by_institution' => 'Participantes por Institución',
            'participants_by_education' => 'Participantes por Nivel Educativo',
            'category_participants' => 'Participantes por Categoría',
            'month_participants' => 'Participantes por Mes',
            'attendance_rate_by_category' => 'Tasa de Asistencia por Categoría',
            // Mapeos adicionales para las claves antiguas
            'categoryChart' => 'Eventos por Categoría',
            'institutionChart' => 'Eventos por Institución',
            'monthChart' => 'Eventos por Mes',
            'participantsChart' => 'Eventos con Más Participantes',
            'genderChart' => 'Participantes por Género',
            'ageChart' => 'Participantes por Edad',
            'institutionParticipantsChart' => 'Participantes por Institución',
            'educationChart' => 'Participantes por Nivel Educativo',
            'categoryParticipantsChart' => 'Participantes por Categoría',
            'monthParticipantsChart' => 'Participantes por Mes',
            'attendanceChart' => 'Tasa de Asistencia por Categoría'
        ];
        
        // Organizar todas las imágenes disponibles por secciones
        $sectionedCharts = [
            'EVENTOS' => [
                'events_by_category',
                'categoryChart',
                'events_by_institution',
                'institutionChart',
                'events_by_month',
                'monthChart',
                'events_with_most_participants',
                'participantsChart'
            ],
            'PARTICIPANTES' => [
                'participants_by_gender',
                'genderChart',
                'participants_by_age',
                'ageChart',
                'participants_by_institution',
                'institutionParticipantsChart',
                'participants_by_education',
                'educationChart'
            ],
            'ANÁLISIS CRUZADO' => [
                'category_participants',
                'categoryParticipantsChart',
                'month_participants',
                'monthParticipantsChart'
            ],
            'ASISTENCIA' => [
                'attendance_rate_by_category',
                'attendanceChart'
            ]
        ];
        
        // Recorremos cada sección
        foreach ($sectionedCharts as $sectionTitle => $chartKeys) {
            // Agregar título de sección
            $sectionDrawing = $this->createSectionTitle($sectionTitle, 'B' . $startRow);
            $drawings[] = $sectionDrawing;
            $startRow += 4; // Aumentado el espacio después del título de sección
            
            // Buscar todas las imágenes correspondientes a esta sección
            foreach ($chartKeys as $chartKey) {
                $found = false;
                
                // Buscar coincidencias exactas primero
                foreach ($this->chartImagePaths as $chartImage) {
                    if ($chartImage['name'] === $chartKey) {
                        $found = true;
                        $this->addChartDrawing($drawings, $chartImage, $chartTitles[$chartKey] ?? ucfirst(str_replace('_', ' ', $chartKey)), $startRow);
                        $startRow += 30; // Aumentado el espacio entre imágenes
                        break;
                    }
                }
                
                // Si no encontramos coincidencia exacta, buscar coincidencia parcial
                if (!$found) {
                    foreach ($this->chartImagePaths as $chartImage) {
                        // Verificar si el nombre del archivo contiene la clave del gráfico
                        if (strpos($chartImage['name'], $chartKey) !== false) {
                            $found = true;
                            $this->addChartDrawing($drawings, $chartImage, $chartTitles[$chartKey] ?? ucfirst(str_replace('_', ' ', $chartKey)), $startRow);
                            $startRow += 30; // Aumentado el espacio entre imágenes
                            break;
                        }
                    }
                }
            }
            
            $startRow += 5; // Aumentado el espacio adicional entre secciones
        }
        
        // Si no se encontraron gráficos organizados por sección, mostrarlos todos en orden
        if (count($drawings) <= count($sectionedCharts)) {
            $drawings = [];
            $startRow = 2;
            
            // Primero mostramos el título general
            $drawings[] = $this->createSectionTitle('TODOS LOS GRÁFICOS', 'B' . $startRow);
            $startRow += 4; // Aumentado el espacio
            
            // Luego añadimos todos los gráficos disponibles
            foreach ($this->chartImagePaths as $chartImage) {
                // Excluir las imágenes de títulos que ya creamos
                if (strpos($chartImage['name'], 'title_') === false) {
                    // Obtener un título descriptivo para esta imagen
                    $title = $this->getDescriptiveTitleForChart($chartImage['name']);
                    
                    // Añadir título sobre el gráfico
                    $titleDrawing = $this->createChartTitle($title, 'B' . $startRow);
                    $drawings[] = $titleDrawing;
                    $startRow += 3; // Aumentado el espacio
                    
                    // Añadir el gráfico
                    $drawing = new Drawing();
                    $drawing->setName('Chart ' . $chartImage['name']);
                    $drawing->setDescription($chartImage['name']);
                    $drawing->setPath($chartImage['path']);
                    $drawing->setWidth(800);
                    $drawing->setCoordinates('B' . $startRow);
                    $drawings[] = $drawing;
                    
                    $startRow += 28; // Aumentado el espacio para la siguiente imagen
                }
            }
        }
        
        return $drawings;
    }
    
    /**
     * Añade un dibujo de gráfico con su título descriptivo
     */
    private function addChartDrawing(&$drawings, $chartImage, $title, $startRow)
    {
        // Primero añadimos el título del gráfico
        $titleDrawing = $this->createChartTitle($title, 'B' . $startRow);
        $drawings[] = $titleDrawing;
        $startRow += 3;
        
        // Luego añadimos el gráfico
        $drawing = new Drawing();
        $drawing->setName('Chart ' . $chartImage['name']);
        $drawing->setDescription($chartImage['name']);
        $drawing->setPath($chartImage['path']);
        $drawing->setWidth(800);
        $drawing->setCoordinates('B' . $startRow);
        $drawings[] = $drawing;
        
        return $startRow;
    }
    
    /**
     * Obtiene un título descriptivo para un gráfico basado en su nombre de archivo
     */
    private function getDescriptiveTitleForChart($chartName)
    {
        $titles = [
            // Mapeo simplificado de nombres de archivos a títulos en español
            'events_by_category' => 'CATEGORÍAS',
            'category' => 'CATEGORÍAS',
            'categoryChart' => 'CATEGORÍAS',
            
            'events_by_institution' => 'INSTITUCIONES',
            'institution' => 'INSTITUCIONES',
            'institutionChart' => 'INSTITUCIONES',
            
            'events_by_month' => 'MESES',
            'month' => 'MESES',
            'monthChart' => 'MESES',
            
            'events_with_most_participants' => 'EVENTOS DESTACADOS',
            'participantsChart' => 'EVENTOS DESTACADOS',
            
            'participants_by_gender' => 'GÉNERO',
            'gender' => 'GÉNERO',
            'genderChart' => 'GÉNERO',
            
            'participants_by_age' => 'EDADES',
            'age' => 'EDADES',
            'ageChart' => 'EDADES',
            
            'participants_by_institution' => 'INSTITUCIONES',
            'institutionParticipantsChart' => 'INSTITUCIONES',
            
            'participants_by_education' => 'NIVEL EDUCATIVO',
            'education' => 'NIVEL EDUCATIVO',
            'educationChart' => 'NIVEL EDUCATIVO',
            
            'category_participants' => 'PARTICIPANTES POR CATEGORÍA',
            'categoryParticipantsChart' => 'PARTICIPANTES POR CATEGORÍA',
            
            'month_participants' => 'PARTICIPANTES POR MES',
            'monthParticipantsChart' => 'PARTICIPANTES POR MES',
            
            'attendance_rate_by_category' => 'ASISTENCIA POR CATEGORÍA',
            'attendance' => 'ASISTENCIA',
            'attendanceChart' => 'ASISTENCIA POR CATEGORÍA'
        ];
        
        // Buscar coincidencia exacta
        if (isset($titles[$chartName])) {
            return $titles[$chartName];
        }
        
        // Buscar coincidencia parcial
        foreach ($titles as $key => $title) {
            if (strpos($chartName, $key) !== false) {
                return $title;
            }
        }
        
        // Si no hay coincidencia, crear un título simplificado
        $translated = str_replace('_', ' ', $chartName);
        
        // Traducir palabras comunes en inglés a español
        $englishToSpanish = [
            'events' => '',
            'event' => '',
            'participants' => 'PARTICIPANTES',
            'participant' => 'PARTICIPANTE',
            'by' => 'POR',
            'category' => 'CATEGORÍA',
            'institution' => 'INSTITUCIÓN',
            'month' => 'MES',
            'gender' => 'GÉNERO',
            'age' => 'EDAD',
            'education' => 'EDUCACIÓN',
            'attendance' => 'ASISTENCIA',
            'rate' => 'TASA',
            'chart' => '',
            'with' => 'CON',
            'most' => 'MÁS'
        ];
        
        foreach ($englishToSpanish as $eng => $spa) {
            $translated = str_ireplace($eng, $spa, $translated);
        }
        
        $translated = trim(preg_replace('/\s+/', ' ', $translated));
        return strtoupper($translated);
    }
    
    /**
     * Crea un título para un gráfico
     */
    private function createChartTitle($title, $coordinates)
    {
        $drawing = new Drawing();
        $drawing->setName('ChartTitle ' . $title);
        $drawing->setDescription($title);
        
        // Crear una imagen con el título pero con un estilo diferente al de sección
        $imagePath = $this->createTitleImage($title, [
            'bgColor' => [240, 240, 240], // Gris claro
            'textColor' => [46, 115, 223], // Azul
            'fontSize' => 14,
            'height' => 24
        ]);
        
        $drawing->setPath($imagePath);
        $drawing->setWidth(800); // Aumentado para que sea más ancho y consistente
        $drawing->setHeight(24);
        $drawing->setCoordinates($coordinates);
        
        // Agregar la ruta para eliminarla después
        $this->chartImagePaths[] = [
            'name' => 'title_chart_' . $title,
            'path' => $imagePath
        ];
        
        return $drawing;
    }
    
    /**
     * Crea un objeto para mostrar el título de sección
     */
    private function createSectionTitle($title, $coordinates)
    {
        $drawing = new Drawing();
        $drawing->setName('Title ' . $title);
        $drawing->setDescription($title);
        
        // Crear una imagen con el título
        $imagePath = $this->createTitleImage($title, [
            'bgColor' => [232, 240, 254], // Azul claro
            'textColor' => [46, 115, 223], // Azul oscuro
            'fontSize' => 16,
            'height' => 30
        ]);
        
        $drawing->setPath($imagePath);
        $drawing->setWidth(800); // Aumentado para que sea más ancho y consistente
        $drawing->setHeight(30);
        $drawing->setCoordinates($coordinates);
        
        // Agregar la ruta para eliminarla después
        $this->chartImagePaths[] = [
            'name' => 'title_section_' . $title,
            'path' => $imagePath
        ];
        
        return $drawing;
    }
    
    /**
     * Crea una imagen con el texto del título
     */
    private function createTitleImage($text, $options = [])
    {
        // Valores predeterminados
        $defaults = [
            'bgColor' => [232, 240, 254], // Azul claro
            'textColor' => [46, 115, 223], // Azul oscuro
            'fontSize' => 16,
            'height' => 30
        ];
        
        // Combinar opciones
        $options = array_merge($defaults, $options);
        
        // Crear una imagen con texto
        $width = 800; // Aumentado para que sea más ancho
        $height = $options['height'];
        $image = imagecreatetruecolor($width, $height);
        
        // Color de fondo
        $bgColor = imagecolorallocate($image, $options['bgColor'][0], $options['bgColor'][1], $options['bgColor'][2]);
        imagefill($image, 0, 0, $bgColor);
        
        // Color de texto
        $textColor = imagecolorallocate($image, $options['textColor'][0], $options['textColor'][1], $options['textColor'][2]);
        
        // Fuente y posición del texto
        $fontSize = $options['fontSize'];
        $fontFile = 5; // Fuente incorporada en GD
        
        // Calcular posición vertical centrada
        $verticalPosition = ($height - imagefontheight($fontFile)) / 2;
        
        // Dibujar el texto con un tamaño más grande y centrado
        $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
        imagestring($image, $fontFile, 30, $verticalPosition, $text, $textColor);
        
        // Guardar la imagen como archivo temporal
        $tempFilePath = sys_get_temp_dir() . '/title_' . Str::slug($text) . '_' . Str::random(8) . '.png';
        imagepng($image, $tempFilePath);
        imagedestroy($image);
        
        return $tempFilePath;
    }

    /**
     * Elimina las imágenes temporales al destruir el objeto
     */
    public function __destruct()
    {
        foreach ($this->chartImagePaths as $chartImage) {
            if (file_exists($chartImage['path'])) {
                unlink($chartImage['path']);
            }
        }
    }
    
    /**
     * Guarda una imagen en base64 como archivo temporal
     */
    private function saveBase64ImageAsFile($base64ImageData, $chartName)
    {
        // Verificar que la imagen esté en formato base64
        if (strpos($base64ImageData, 'data:image') === 0) {
            // Extraer la parte de datos
            $imageData = substr($base64ImageData, strpos($base64ImageData, ',') + 1);
            
            // Decodificar la imagen
            $decodedImage = base64_decode($imageData);
            
            // Crear un nombre de archivo temporal
            $tempFilePath = sys_get_temp_dir() . '/' . $chartName . '_' . Str::random(8) . '.png';
            
            // Guardar la imagen en disco
            file_put_contents($tempFilePath, $decodedImage);
            
            // Agregar a la lista de imágenes
            $this->chartImagePaths[] = [
                'name' => ucfirst(str_replace('_', ' ', $chartName)),
                'path' => $tempFilePath
            ];
        }
    }
} 