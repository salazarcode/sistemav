<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estadísticas</title>
    <style>
        :root {
            --primary-color: #3366CC;
            --primary-dark: #2A579A;
            --secondary-color: #FF9933;
            --accent-color: #66CC99;
            --light-color: #F5F8FF;
            --dark-color: #334455;
            --border-color: #D9E2F2;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0 15px;
            background-color: white;
        }
        
        .container {
            width: 100%;
            padding: 10px 0;
        }
        
        .header {
            text-align: center;
            padding: 12px 0;
            margin-bottom: 15px;
            background: var(--primary-dark);
            color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 24px;
            text-align: center;
            margin: 5px 0;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            letter-spacing: 0.5px;
        }
        
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 10px;
            color: var(--dark-color);
            background-color: var(--light-color);
            padding: 8px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }
        
        h2 {
            font-size: 15px;
            margin: 20px 0 10px;
            padding: 5px 10px;
            border-left: 4px solid var(--primary-color);
            background-color: var(--light-color);
            color: var(--dark-color);
            clear: both;
        }
        
        h3 {
            font-size: 13px;
            margin: 15px 0 8px;
            color: var(--primary-color);
            padding-bottom: 3px;
            border-bottom: 1px solid var(--border-color);
            clear: both;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th, td {
            padding: 6px;
            text-align: left;
            border: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: var(--light-color);
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 20px;
            padding: 8px 0;
            background-color: var(--dark-color);
            color: white;
            text-align: center;
            font-size: 9px;
            border-radius: 4px;
            clear: both;
        }
        
        .page-break {
            page-break-after: always;
            clear: both;
            height: 0;
        }
        
        .chart-container {
            width: 100%;
            margin: 10px 0 20px 0;
            text-align: center;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 5px;
        }
        
        .chart-container img {
            width: 100%;
            max-width: 500px;
            height: auto;
            margin: 0 auto;
            display: block;
            border-radius: 4px;
            object-fit: contain;
            aspect-ratio: 16/9;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 10px;
            table-layout: fixed;
        }
        
        .summary-table td {
            width: 50%;
            padding: 0;
            vertical-align: top;
            border: none;
        }
        
        .summary-box {
            background-color: var(--light-color);
            border-left: 3px solid var(--primary-color);
            padding: 12px;
            min-height: 70px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 0 4px 4px 0;
            margin-bottom: 0;
            display: block;
        }
        
        .summary-box .summary-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-size: 11px;
        }
        
        .summary-box .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .metric-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 10px;
            table-layout: fixed;
        }
        
        .metric-table td {
            width: 50%;
            padding: 0;
            vertical-align: top;
            border: none;
        }
        
        .metric-container {
            background-color: var(--light-color);
            border-left: 3px solid var(--secondary-color);
            padding: 15px;
            min-height: 80px;
            text-align: center;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: block;
        }
        
        .metric-container .metric-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .metric-container .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .section-divider {
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 20px 0;
            border-radius: 2px;
        }

        .executive-summary {
            background-color: var(--light-color);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--accent-color);
            padding: 10px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }

        .executive-summary h3 {
            color: var(--accent-color);
            border-bottom: none;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .executive-summary p {
            margin: 5px 0;
            font-size: 11px;
            line-height: 1.5;
        }

        .highlight {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .two-columns {
            column-count: 2;
            column-gap: 20px;
            margin-bottom: 15px;
        }

        .compact-table {
            font-size: 9px;
        }
        
        .compact-table th, .compact-table td {
            padding: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Estadísticas de Eventos</h1>
    </div>
    
    <div class="header-info">
        <p>Generado el: {{ $date }} | Por: {{ $user }} | Período: {{ $period }}</p>
    </div>
    
    <!-- RESUMEN EJECUTIVO -->
    <div class="executive-summary">
        <h3>Resumen Ejecutivo</h3>
        <p>
            Durante el período analizado se registraron <span class="highlight">{{ $totalEvents }}</span> eventos con un total de <span class="highlight">{{ $totalParticipants }}</span> participantes. 
            La tasa de asistencia global fue de <span class="highlight">{{ $attendanceRate }}%</span>.
        </p>
        <p>
            Actualmente hay <span class="highlight">{{ $upcomingEvents }}</span> eventos programados, <span class="highlight">{{ $ongoingEvents }}</span> en curso y <span class="highlight">{{ $pastEvents }}</span> ya finalizados.
            El promedio de participantes por evento es de <span class="highlight">{{ $totalEvents > 0 ? number_format($totalParticipants / $totalEvents, 1) : 0 }}</span> personas.
        </p>
    </div>
    
    <div class="section-divider"></div>
    
    <!-- SECCIÓN 1: RESUMEN DE EVENTOS -->
    <h2>Resumen General de Eventos</h2>
    
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Total de Eventos</div>
                    <div class="summary-value">{{ $totalEvents }}</div>
                </div>
            </td>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Total de Participantes</div>
                    <div class="summary-value">{{ $totalParticipants }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Eventos Futuros</div>
                    <div class="summary-value">{{ $upcomingEvents }}</div>
                </div>
            </td>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Eventos en Curso</div>
                    <div class="summary-value">{{ $ongoingEvents }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Eventos Pasados</div>
                    <div class="summary-value">{{ $pastEvents }}</div>
                </div>
            </td>
            <td>
                <div class="summary-box">
                    <div class="summary-title">Tasa de Asistencia</div>
                    <div class="summary-value">{{ $attendanceRate }}%</div>
                </div>
            </td>
        </tr>
    </table>
    
    <div class="two-columns">
        <div>
            <h3>Distribución por Categoría</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventsByCategory as $category)
                        <tr>
                            <td>{{ $category['name'] }}</td>
                            <td class="text-center">{{ $category['count'] }}</td>
                            <td class="text-center">{{ $totalEvents > 0 ? number_format(($category['count'] / $totalEvents) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            <h3>Distribución por Organización</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Organización</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventsByOrganization as $institution)
                        <tr>
                            <td>{{ $institution['name'] }}</td>
                            <td class="text-center">{{ $institution['count'] }}</td>
                            <td class="text-center">{{ $totalEvents > 0 ? number_format(($institution['count'] / $totalEvents) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <h3>Eventos por Mes</h3>
    <table class="compact-table">
        <thead>
            <tr>
                <th>Mes</th>
                <th>Cantidad de Eventos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventsByMonth as $month)
                <tr>
                    <td>{{ $month['month'] }}</td>
                    <td class="text-center">{{ $month['count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="page-break"></div>
    
    <!-- SECCIÓN 2: RESUMEN DE PARTICIPANTES -->
    <h2>Análisis de Participantes</h2>
    
    <table class="metric-table">
        <tr>
            <td>
                <div class="metric-container">
                    <div class="metric-title">Total de Participantes</div>
                    <div class="metric-value">{{ $totalParticipants }}</div>
                </div>
            </td>
            <td>
                <div class="metric-container">
                    <div class="metric-title">Promedio por Evento</div>
                    <div class="metric-value">{{ $totalEvents > 0 ? number_format($totalParticipants / $totalEvents, 1) : 0 }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="metric-container">
                    <div class="metric-title">Tasa de Asistencia</div>
                    <div class="metric-value">{{ $attendanceRate }}%</div>
                </div>
            </td>
            <td>
                <div class="metric-container">
                    <div class="metric-title">Eventos Realizados</div>
                    <div class="metric-value">{{ $pastEvents }}</div>
                </div>
            </td>
        </tr>
    </table>
    
    <div class="two-columns">
        <div>
            <h3>Distribución por Género</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Género</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantsByGender as $gender)
                        <tr>
                            <td>{{ $gender['name'] }}</td>
                            <td class="text-center">{{ $gender['count'] }}</td>
                            <td class="text-center">{{ $totalParticipants > 0 ? number_format(($gender['count'] / $totalParticipants) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            <h3>Distribución por Edad</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Rango de Edad</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantsByAge as $age)
                        <tr>
                            <td>{{ $age['name'] }}</td>
                            <td class="text-center">{{ $age['count'] }}</td>
                            <td class="text-center">{{ $totalParticipants > 0 ? number_format(($age['count'] / $totalParticipants) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="two-columns">
        <div>
            <h3>Distribución por Organización</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Organización</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantsByOrganization as $institution)
                        <tr>
                            <td>{{ $institution['name'] }}</td>
                            <td class="text-center">{{ $institution['count'] }}</td>
                            <td class="text-center">{{ $totalParticipants > 0 ? number_format(($institution['count'] / $totalParticipants) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div>
            <h3>Distribución por Nivel Educativo</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Nivel Educativo</th>
                        <th>Cantidad</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantsByEducation as $education)
                        <tr>
                            <td>{{ $education['name'] }}</td>
                            <td class="text-center">{{ $education['count'] }}</td>
                            <td class="text-center">{{ $totalParticipants > 0 ? number_format(($education['count'] / $totalParticipants) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="page-break"></div>
    
    <!-- SECCIÓN 3: GRÁFICOS -->
    <h2>Visualización de Datos</h2>
    
    @if(isset($chartImages) && !empty($chartImages))
        <div class="two-columns">
            <div>
                <h3>Eventos por Categoría</h3>
                @if(isset($chartImages['categoryChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['categoryChart'] }}" alt="Gráfico de categorías">
                    </div>
                @endif
            </div>
            
            <div>
                <h3>Eventos por Organización</h3>
                @if(isset($chartImages['organizationChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['organizationChart'] }}" alt="Gráfico de organizaciones">
                    </div>
                @endif
            </div>
        </div>
        
        <div class="two-columns">
            <div>
                <h3>Eventos por Mes</h3>
                @if(isset($chartImages['monthChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['monthChart'] }}" alt="Gráfico de meses">
                    </div>
                @endif
            </div>
            
            <div>
                <h3>Top Eventos por Participantes</h3>
                @if(isset($chartImages['participantsChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['participantsChart'] }}" alt="Gráfico de participantes">
                    </div>
                @endif
            </div>
        </div>
        
        <div class="page-break"></div>
        
        <div class="two-columns">
            <div>
                <h3>Participantes por Género</h3>
                @if(isset($chartImages['genderChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['genderChart'] }}" alt="Gráfico de género">
                    </div>
                @endif
            </div>
            
            <div>
                <h3>Participantes por Edad</h3>
                @if(isset($chartImages['ageChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['ageChart'] }}" alt="Gráfico de edad">
                    </div>
                @endif
            </div>
        </div>
        
        <div class="two-columns">
            <div>
                <h3>Participantes por Organización</h3>
                @if(isset($chartImages['organizationParticipantsChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['organizationParticipantsChart'] }}" alt="Gráfico de organizaciones">
                    </div>
                @endif
            </div>
            
            <div>
                <h3>Participantes por Nivel Educativo</h3>
                @if(isset($chartImages['educationChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['educationChart'] }}" alt="Gráfico de educación">
                    </div>
                @endif
            </div>
        </div>
        
        @if(isset($chartImages['attendanceChart']))
        <h3>Tasa de Asistencia</h3>
        <div class="chart-container">
            <img src="{{ $chartImages['attendanceChart'] }}" alt="Gráfico de asistencia">
        </div>
        @endif
        
        <div class="page-break"></div>
        
        <!-- NUEVA SECCIÓN: ANÁLISIS CRUZADO DE PARTICIPANTES -->
        <h2>Análisis Cruzado de Participantes</h2>
        
        <div class="two-columns">
            <div>
                <h3>Participantes por Categoría</h3>
                @if(isset($chartImages['categoryParticipantsChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['categoryParticipantsChart'] }}" alt="Gráfico de participantes por categoría">
                    </div>
                @endif
                
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participantsByCategory as $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td class="text-center">{{ $category['count'] }}</td>
                                <td class="text-center">{{ $totalParticipants > 0 ? number_format(($category['count'] / $totalParticipants) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div>
                <h3>Participantes por Mes</h3>
                @if(isset($chartImages['monthParticipantsChart']))
                    <div class="chart-container">
                        <img src="{{ $chartImages['monthParticipantsChart'] }}" alt="Gráfico de participantes por mes">
                    </div>
                @endif
                
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participantsByMonth as $month)
                            <tr>
                                <td>{{ $month['month'] }}</td>
                                <td class="text-center">{{ $month['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>Debido a las limitaciones técnicas, los gráficos no se pudieron incluir en este PDF. Para ver los gráficos completos, visite la página de estadísticas en la plataforma.</p>
    @endif
    
    <div class="page-break"></div>
    
    <!-- NUEVA SECCIÓN: LISTA DE EVENTOS INCLUIDOS -->
    <h2>Listado de Eventos Incluidos</h2>
    
    <p>Este reporte incluye estadísticas sobre los siguientes {{ count($eventsList) }} eventos:</p>
    
    <table class="compact-table">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Organización</th>
                <th>Participantes</th>
                <th>Ubicación</th>
                <th>Categorías</th>
                <th>Fechas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventsList as $event)
                <tr>
                    <td>{{ $event['name'] }}</td>
                    <td>{{ $event['organization'] }}</td>
                    <td class="text-center">{{ $event['participants'] }}</td>
                    <td>{{ $event['location'] }}</td>
                    <td>{{ $event['categories'] }}</td>
                    <td>{{ $event['dates'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Sistema de Gestión de Eventos - {{ date('Y') }} | Todos los derechos reservados</p>
    </div>
</body>
</html> 